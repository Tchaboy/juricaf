<?php
$xmlfile='tmp/xmls/';
$mois=['01'=>'janvier','02'=>'février','03'=>'mars','04'=>'avril','05'=>'mai','06'=>'juin','07'=>'juillet','08'=>'août','09'=>'septembre','10'=>'octobre','11'=>'novembre','12'=>'décembre'];

$fichiers=scandir('tmp/pages');
$i=0;

foreach ($fichiers as $k => $v) {

  if ($k!=0 && $k!=1){
  $content=file('tmp/pages/'.$v);
  $content=$content[0];
  preg_match("#<p class=\"date\">(.+)</p>#iU",$content,$date);
  $date=$date[1];
  // print_r($date."\n");
  $j=$date[0].$date[1];
  $m=$date[3].$date[4];
  $a=$date[6].$date[7].$date[8].$date[9];

  $date=$a.'-'.$m.'-'.$j;

  preg_match('/<p class="date">.+<\/p><h1>([^<]+)/',$content,$titre);
  $titre=$titre[1];


  $juridiction='Tribunal suprême';  //Tribunal suprême  sur le site dans ce site www.tribunal-supreme           ou Cour de révision sur le site legimonaco.mc

  preg_match("#<div class=\"content\">.+<div class=\"print-button\">#",$content,$content);

  $content=$content[0];
  // $content=strip_tags($content);

  // $content=preg_replace('#</h[1-4]*>#',"\n",$content);
  $content=preg_replace('#</p>#',"\n",$content);
  // $content=preg_replace('#<p>#',"\n",$content);
  // $content=preg_replace('#^>#',"",$content);
  // $content=preg_replace('#</p>#',"\n",$content);


  // $content=preg_replace('#^[<p].+$[>]>#',"\n",$content);
  // $content=preg_replace('#<p style="text-align: right;"><strong>#',"\n",$content);
  // $content=preg_replace('#<strong>#',"\n",$content);
  // $content=preg_replace('#<br>#',"\n",$content);

  $content=preg_replace('#<[^>]+>#', "", $content);
  // $content= str_replace("\n\n"," ",$content);

  $content=strip_tags($content);
  $content=html_entity_decode($content);
  $content=str_replace("+[\n]","",$content);

  $d=new DateTime($date);
  $date_min=new DateTime('2019-05-01');

  if($d>$date_min){

  $output = fopen($xmlfile.$i, 'w');
  fwrite($output, '<?xml version="1.0" encoding="utf8"?>');
  fwrite($output,"\n");
  fwrite($output, "<DOCUMENT>\n");
  fwrite($output, "<DATE_ARRET>$date</DATE_ARRET>\n");
  fwrite($output, "<JURIDICTION>$juridiction</JURIDICTION>\n");
  fwrite($output, "<NUM_ARRET>$numero</NUM_ARRET>\n");
  fwrite($output, "<PAYS>Monaco</PAYS>\n");
  fwrite($output, "<TEXTE_ARRET>$content</TEXTE_ARRET>\n");
  $datefr=$j.' '.$mois[$m].' '.$a;
  fwrite($output,"<TITRE>Monaco, $juridiction, $datefr, $numero<TITRE>\n");
  fwrite($output, "<TYPE>arret</TYPE>\n");
  fwrite($output, "</DOCUMENT>\n");
  fclose($output);

}
  $i=$i+1;




}
}


?>
