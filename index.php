<?php

function downloadFile($url, $localPath)
{
      // Inicializa o curl
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $data = curl_exec($ch);
      curl_close($ch);

      // Salva o arquivo localmente
      file_put_contents($localPath, $data);
}

// URLs do vídeo e do áudio
$videoUrl = 'https://www.shutterstock.com/shutterstock/videos/1022870788/preview/stock-footage-abstract-seamless-background-blue-purple-spectrum-looped-animation-fluorescent-ultraviolet-light.mp4';
$audioUrl = 'https://assets.snapmuse.com/tracks/v/128/IEROD1902600.mp3';

// Caminho local onde o vídeo e o áudio serão salvos
$videoPath = 'videos/video.mp4';
$audioPath = 'audios/audio.mp3';

// Baixa o vídeo e o áudio utilizando a função downloadFile()
downloadFile($videoUrl, $videoPath);
downloadFile($audioUrl, $audioPath);

// Caminho local onde o vídeo resultante será salvo
$outputPath = 'video_final.mp4';

// Executa o comando para mesclar o vídeo e o áudio

$audio_duration_cmd = "ffprobe -i $audioPath -show_entries format=duration -v quiet -of csv=\"p=0\"";
$audio_duration = exec($audio_duration_cmd);

$cmd = "ffmpeg -stream_loop -1 -i $videoPath -i $audioPath -c:v copy -map 0:v -map 1:a -t $audio_duration -y $outputPath";

// Execute FFmpeg command
exec($cmd);

// Check if output file was created successfully
if (file_exists($outputPath)) {
      echo "Output file created successfully: {$outputPath}";
} else {
      echo "Error: Output file was not created";
}
