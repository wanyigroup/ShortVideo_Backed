<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Config;
use think\facade\Db;

class Convert extends Command
{
    protected function configure()
    {
        $this->setName('convert')
            ->addArgument('cmd', Argument::REQUIRED, "Command")
            //->addOption('vid', null, Option::VALUE_OPTIONAL, 'VideoID')
            ->setDescription('Video Convert command');
    }

    protected function execute(Input $input, Output $output)
    {
        $ID = Db::table('app_videoqueue')->where('status', '0')->value('id');
        if(empty($ID)) {
            $output->writeln("队列为空,终止运行!");
            die();
        }

        $FFBin = Config::get('bin.ffmpeg');
        $FPBin = Config::get('bin.ffprobe');
        $FPATH = app()->getRootPath()."original/{$ID}.tmp";
        $OPATH = app()->getRootPath().'public/media/';
        $LOGPATH = app()->getRuntimePath()."{$ID}.log";

        if (!file_exists($FPATH)) { // 源文件不存在删除记录
            Db::table('app_videoqueue')->delete($ID);
            $output->writeln($ID.'视频源不存在,删除记录终止运行!');
            die();
        }

        $cmd = trim($input->getArgument('cmd'));
        if ($cmd == 'parse') {
            $CMD = $FPBin.' -v quiet -print_format json -show_format -show_streams "'.$FPATH.'"';
            $json = shell_exec($CMD);
            Db::table('app_videoqueue')->save(['id' => $ID, 'metadata' => $json]);
            //var_dump(json_decode($json,true));
            $output->writeln('File Info has Updated!');
        }

        if ($cmd == 'c2mp4') {
            $input_lines = shell_exec($FPBin.' -v error -select_streams v:0 -show_entries stream=width,height,duration,bit_rate -of default=noprint_wrappers=1 '.$FPATH);
            preg_match_all('/width=(?<w>.*)\sheight=(?<h>.*)\sduration=(?<dur>.*)\sbit_rate=(?<bit>.*)/i', $input_lines, $output_array);
            //var_dump($output_array);
            $vbit = (isset($output_array['bit']['0']) && !empty($output_array['bit']['0']) ? $output_array['bit']['0'] : '1024000');
            $vmax = $vbit / 1000;
            if( $vmax >= 8000 ) {//1080P
                $VMR = 8000; $VWH = 1080;
            } elseif($vmax >= 5000 ) {
                $VMR = 5000; $VWH = 720;
            } elseif($vmax >= 2500 ) {
                $VMR = 2500; $VWH = 480;
            } elseif($vmax >= 1000 ) {
                $VMR = 1000; $VWH = 360;
            } else {
                $VMR = $vmax; $VWH = 240;
            }
            $vbs = $VMR * 2;
            $output->writeln("视频源码率: $vmax | 突发码率: $vbs");

            // 开始转码
            $output->writeln("开始转码");
            Db::table('app_videoqueue')->save(['id' => $ID, 'status' => '2']);
            $commandcovert = $FFBin." -hide_banner -y -i $FPATH -threads 0 -c:v libx264 -maxrate ${VMR}k -bufsize ${vbs}k -max_muxing_queue_size 9999 -preset slow -c:a aac -b:a 128k -movflags faststart -f mp4 ".$OPATH."mp4/{$ID}.mp4 </dev/null >/dev/null 2>$LOGPATH";
            //Debug日志 -loglevel panic
            //$output->writeln("$commandcovert");
            $json = shell_exec($commandcovert);
            $output->writeln("转码已经完成!");

            // 开始提取图片
            $output->writeln("开始生成视频预览图片!");
            $INPUT = "{$OPATH}/mp4/{$ID}.mp4";
            $tmbnum = 10;
            $total_frames = trim(shell_exec("mediainfo --Inform='Video;%FrameCount%' $INPUT"));
            // var_dump($total_frames);
            $rate = round($total_frames/$tmbnum);

            $TMBPATH = "$OPATH/tmb/$ID/";
            @mkdir($TMBPATH);
            $maketmb = shell_exec("ffmpeg -loglevel panic -y -i $INPUT -qscale:v 1 -vframes $total_frames -vf \"select='not(mod(n,$rate))',scale=320:-2\" -vsync vfr $TMBPATH/%01d.jpg");
            $output->writeln("视频预览图片生成完毕!");

            // 转码完成
            $output->writeln("更新任务状态");
            Db::table('app_videoqueue')->save(['id' => $ID, 'status' => '1']);

            // 加入待审列表
            $output->writeln("开始发布视频!");
            $d = Db::table('app_videoqueue')->where('id', $ID)->findOrEmpty();
            //var_dump($orgdata);

            list($width, $height, $type, $attr) = getimagesize("$TMBPATH/1.jpg");

            $newdata = [
                "uid" => '1',
                "cid" => '1',
                "sid" => '0',
                "author" => "小姐姐",
                "feature" => '1',
                "vkey" => $d['hash'],
                "title" => $d['newname'],
                "description" => $d['newname'],
                "tags" => "",
                //"image" => $imgsrc,
                //"file" => $mp4src,
                "duration" => round($output_array['dur']['0']),
                "width" => $width,
                "height" => $height,
                "views" => 0,
                "likes" => 0,
                // "createtime" =>
                "status" => '0',
            ];
            //var_dump($newdata);
            if(empty(Db::table('app_video')->where('vkey', $d['hash'])->value('vkey')))
            {
                $video =  \app\common\model\Video::create($newdata);
                $vid = $video->id;
                if($vid) {
                    @rename($INPUT,$OPATH."mp4/$vid.mp4");
                    @rename($TMBPATH,"$OPATH/tmb/$vid/");
                    Db::table('app_video')->save([
                        'id' => $vid,
                        'image' => "media/tmb/$vid/1.jpg",
                        'file' => "media/mp4/$vid.mp4",
                    ]);
                    $output->writeln("文件移动完成!");
                }
                $output->writeln("视频发布成功!");
            } else {
                $vid = Db::table('app_video')->where('vkey', $d['hash'])->value('id');
                Db::table('app_video')->save([
                    'id' => $vid,
                    'image' => "media/tmb/$vid/1.jpg",
                    'file' => "media/mp4/$vid.mp4",
                ]);
                $output->writeln("视频已存在,无需发布!");
            }
            //更新任务状态
            Db::table('app_videoqueue')->save(['id' => $ID, 'status' => '3']);

            //
            // 开始同步视频
            // Todo
            $output->writeln("开始同步视频至存储服务器!");
            $rsync = "rsync -artv --progress {$OPATH} /www/wwwroot/vcdn.afuny.com/media/";
            $output->writeln($rsync);
            shell_exec($rsync);
            $output->writeln("同步视频已完成!");
        }

    }
}
