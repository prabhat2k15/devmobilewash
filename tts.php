<?php
 /*$file = "sound/test.mp3";
  $mp3 = file_get_contents('https://translate.google.com/translate_tts?ie=UTF-8&q=welcome&tl=en-TW&client=tw-ob');
  file_put_contents($file, $mp3);*/
  

require_once('voicerss_tts.php');

$tts = new VoiceRSS;
$voice = $tts->speech([
    'key' => '571c40dafcda4d3fabdca18e960c9198',
    'hl' => 'en-gb',
    'src' => 'welcome back nazmur.r',
    'r' => '0',
    'c' => 'mp3',
    'f' => '44khz_16bit_stereo',
    'ssml' => 'false',
    'b64' => 'true'
]);

echo '<audio src="' . $voice['response'] . '" autoplay="autoplay"></audio>';

        
?>