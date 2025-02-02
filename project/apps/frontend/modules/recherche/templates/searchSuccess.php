<?php if ($json == false) {
use_helper('Text');

$nbResultats = number_format($resultats->response->numFound, 0, ',', ' ');

function replaceBlank($str) {
  return str_replace (' ', '_', $str);
}

function replaceUnderscore($str) {
  return str_replace ('_', ' ', $str);
}

function pathToFlag($str) {
  return urlencode(str_replace("'", '_', replaceBlank($str)));
}

function remplacequery($string) {
  $table = array(
   'analyses:' => '<br><span itemprop="title">Analyse: ', 
   'type_recours:' => '<br><span itemprop="title">Type de recours: ', 
   'references:' => '<br><span itemprop="title">Références: ', 
   'president:' => '<br><span itemprop="title">Président: ', 
   'rapporteur:' => '<br><span itemprop="title">Rapporteur ', 
   'commissaire_gvt:' => '<br><span itemprop="title">Rapporteur public: ', 
   'avocat_gl:' => '<br><span itemprop="title">Avocat général: ', 
   'texte_arret:' => '<br><span itemprop="title">Recherche : ', 
   'fonds_documentaire:' => '<br><span itemprop="title">Fonds documentaire: ', 
   'avocats:' => '<br><span itemprop="title">Avocat: ', 
   'decisions_attaquees:' => '<br><span itemprop="title">Juridiction attaquée: ', 
   'sens_arret:' => '<br><span itemprop="title">Sens :', 
   'saisines:' => '<br><span itemprop="title">Saisine: ', 
   'ecli:' => '<span itemprop="title">ECLI: ', 
   'nor:' => '<span itemprop="title">NOR: ', 
   'type_affaire:' => 'Type d\'affaire: ',
   '(premier avocat general)' => '',
   '(president)' => '',
      '"' => ''   
   );
  return strtr($string, $table);
}

function remplacequerytitre($string) {
  $table = array(
   'analyses:' => 'avec l\'analyse ', 
   'type_recours:' => 'avec pour type de recours ', 
   'references:' => 'avec les références ', 
   'president:' => 'dont les audiences ont été présidées par ', 
   'rapporteur:' => 'qui ont été rapportées par ', 
   'commissaire_gvt:' => ' avec pour le commissaire du gouvernement ', 
   'avocat_gl:' => 'avec pour l\'avocat général ', 
   'fonds_documentaire:' => 'issues du fonds documentaire ', 
   'avocats:' => 'avec pour avocat ', 
   'decisions_attaquees:' => 'ayant fait l\'objet d\'un pouvoi en cassation ', 
   'sens_arret:' => 'ayant pour sens ', 
   'saisines:' => 'ayant été saisis par ', 
   'ecli:' => 'ayant comme numéro ECLI ', 
   'nor:' => 'ayant comme numéro NOR ', 
   'type_affaire:' => 'ayant pour type d\'affaire ',
	'(president)' => '',
	'(premier avocat general)' => '',
	'"' => ''   
   );
  return strtr($string, $table);
}

?>
<div class="recherche">

<div class="affiner">
<a href="http://www.juricaf.org">Accueil</a> > <a href="<?php echo $sf_request->getUri() ?>">Recherche</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="<?php echo $sf_request->getUri().'?format=rss'; ?>"><img src="/images/rss_mini.png" alt="RSS" title="Flux RSS" /></a>
	<div class="affinercols">
		<ul>			
			<li>
				<h4>Termes de la recherche :</h4>
				<p class="recherche_terme"><?php echo remplacequery($query); ?></p>
			</li>
			<li>
				<h4>Tri :</h4>
				<ul>

<?php

//  Suppression des options

$myfacetslink = preg_replace('/^,/', '', $facetslink);
$currentlink = array('module'=>'recherche', 'action'=>'search', 'query' => $query, 'facets'=>$myfacetslink);
if (count($facetsset)) { ?>

  <?php
  $myfacetslink = preg_replace('/^,/', '', $facetslink);
  $noorderlink = $currentlink;
  $noorderlink['facets'] = preg_replace('/^,/', '', preg_replace('/,$/', '', preg_replace('/order:[^:,]+,?/', '', $myfacetslink)));

  foreach($facetsset as $f) { ?>
  <?php
    if (!preg_match('/order:/', $f)) {
      $text = preg_replace('/_/', ' ', preg_replace('/[^:]+:/', '', $f));
      $tmplink = $currentlink;
      $tmplink['facets'] = preg_replace('/^,/', '', preg_replace('/,$/', '', preg_replace('/'.preg_replace('/\|/', '\\\|', $f).',?/', '', $myfacetslink)));
      echo link_to('<li><img src="/images/annuler.png" alt="Annuler" title="Annuler" />Résultats filtrés sur <em>'.$text.'</em></li>', $tmplink);
    }
    else {
      if (preg_match('/order:perti/', $f)) {
        echo link_to('<li><img src="/images/annuler.png" alt="Annuler" title="Annuler" />Résultats trié par pertinence</li>', $noorderlink);
      }
	  if (preg_match('/order:antéchronologique/', $f)) {
        echo link_to('<li><img src="/images/annuler.png" alt="Annuler" title="Annuler" />Résultats trié dans l\'ordre antechronologique</li>', $noorderlink);
      }
      else if (preg_match('/order:chrono/', $f)) {
        echo link_to('<li><img src="/images/annuler.png" alt="Annuler" title="Annuler" />Résultats trié dans l\'ordre chronologique</li>', $noorderlink);
      }
    }
  ?>

</ul><ul>
  <?php
  }

// Metadonnées

foreach($facetsset as $facet) {
  if (preg_match('/^facet_pays_juridiction:/', $facet)) {
    $title_facet['pays_juri'] = replaceUnderscore(str_replace('facet_pays_juridiction:', '', $facet));
  }
  if (preg_match('/^facet_pays:/', $facet)) {
    $title_facet['pays'] = replaceUnderscore(str_replace('facet_pays:', '', $facet));
  }
  if(preg_match('/^facet_juridiction:/', $facet)) {
    $title_facet['juri'] = replaceUnderscore(str_replace('facet_juridiction:', '', $facet));
  }
}

if(isset($title_facet['pays_juri'])) {
  $title_facet = $title_facet['pays_juri'];
}
elseif(isset($title_facet['pays']) && isset($title_facet['juri'])) {
  $title_facet = $title_facet['pays'].' | '.$title_facet['juri'];
}
else {
  if(isset($title_facet['juri'])) { $title_facet = $title_facet['juri']; }
  elseif(isset($title_facet['pays'])) { $title_facet = $title_facet['pays']; }
}
?>

<?php
}

if (trim($query) !== '' || isset($title_facet)) {
  $pays_noindex = array(Guinée
    // "Guinée", Pays non indexés
  );

  if(isset($title_facet) && trim($query) == '') {
    $title = 'Jurisprudences '.$title_facet.'';
    $description = $resultats->response->numFound.' arrêts publiés dans la base de données';
      }
  if(isset($title_facet) && trim($query) !== '') {
    $title = 'Jurisprudences '.remplacequerytitre($query).' - '.$title_facet.'';
    $description = $resultats->response->numFound.' arrêts publiés dans la base de données';
      }
  if(isset($title_facet)) {
    foreach ($pays_noindex as $noindex) {
      if(strpos($title_facet, $noindex) !== true) { $sf_response->addMeta('robots', 'noarchive', false, false, false); }
    }
  }
  if(!isset($title_facet) && trim($query) !== '') {
    $title = 'Jurisprudences '.remplacequerytitre($query).'';
    $description = $resultats->response->numFound.' arrêts publiés dans la base de données';
      }
  slot("metadata");
  include_partial("metadata", array('url_flux' => $sf_request->getUri().'?format=rss', 'titre_flux' => "S'abonner à cette recherche"));
  end_slot();
  $sf_response->setTitle($title);
  $sf_response->addMeta('description', $description);
  $sf_response->addMeta('keywords', $keywords);
}

//  Gestion des facettes

if ($resultats->response->numFound !== 0) {
?>

				
  <?php
  if (!preg_match('/order:/', $facetslink)) {
    echo '';
  }
  else {
    echo '<li>'.link_to('antéchronologique', $noorderlink).'</li>';
  }

  if (preg_match('/order:chrono/', $facetslink)) {
    echo '';
  }
  else {
    $tmplink = $currentlink;
    $tmplink['facets'] = 'order:chrono'.preg_replace('/,?order:[a-z]*,?/', '', $facetslink);
    echo '<li>'.link_to('chronologique', $tmplink, array('rel'  => 'nofollow')).'</li>';
  }

  if (preg_match('/order:pertinence/', $facetslink)) {
    echo '';
  }
  else {
    $tmplink = $currentlink;
    $tmplink['facets'] = 'order:pertinence'.preg_replace('/,?order:[a-z]*,?/', '', $facetslink);
    echo '<li>'.link_to('par pertinence', $tmplink, array('rel'  => 'nofollow')).'</li>';
  } ?>

				</ul>
			</li>
<?php
if(isset($nobots)) { $sf_response->addMeta('robots', 'noindex, nofollow', false, false, false); }
include_component('recherche', 'facets', array('label'=>'Pays &amp; Juridiction', 'id'=>'facet_pays_juridiction', 'facets' => $facets, 'query'=>$query, 'facetslink'=>$facetslink, 'tree' => true, 'mainid' => 'facet_pays'));
?>

		</ul>	 
	
<?php
}

// Affichage des résultats

?>
</div>
</div>
<div class="resultat">
	<h3><?php echo $nbResultats; ?> résultats</h3>
<script><!--
    ////////////////////////////////////////////////
    // Matomo search
    ///////////////////////////////////////////////
--></script>
<?php
foreach ($resultats->response->docs as $resultat) {
  echo '<div class="resultatcols"><a href="'.url_for('@arret?id='.$resultat->id).'"><h3><img src="/images/drapeaux/'.pathToFlag($resultat->pays).'.png" alt="§" /> '.$resultat->titre.'</h3></a>';
  echo '<p>';
  if (isset($resultats->highlighting))
    echo JuricafArret::getExcerpt($resultat, $resultats->highlighting->{$resultat->id});
  else
    echo JuricafArret::getExcerpt($resultat);
  echo '</p>';
  $formation = '';
  if ($resultat->formation) {
    $formation = ', '.$resultat->formation;
  }
 // echo '<div class="extra"></div>';
  echo '</div>';
}
?>
	<div class="navigation">
	<?php if ($resultats->response->numFound > 10) { echo include_partial('pager', array('pager' => $pager, 'currentlink' => $currentlink)); } ?>
	</div>
</div>

<div style="clear:both;">&nbsp;</div>
</div>
<script type="text/javascript">
<!--
resultats = $('.resultats').css('height');
resultats = parseInt(resultats.substring(0,(resultats).length-2));
facets = $('.facets').css('height');
facets = parseInt(facets.substring(0,(facets).length-2));
if(facets > resultats) {
  $('.facets').css('height', resultats+'px');
  $('.facets').css('overflow', 'auto');
}
// -->
</script>
<?php } 

else { // JSON
 
  $nbResultats = $resultats->response->numFound;

// Ouverture de l'objet JSON
 echo '{ ';
 
 echo "\"nb_resultat\" : $nbResultats, \"docs\" : [ ";
 
 // Gestion de la derniere boucle
 $total = count($resultats->response->docs);
 $i = 0;
 
 foreach ($resultats->response->docs as $resultat) {
	echo '{ ';
		echo '"id" : "' . $resultat->id . '", ';
		echo '"pays" : "' . $resultat->pays . '", ';
		echo '"titre" : "' . $resultat->titre . '", ';
		echo '"formation" : "' . $resultat->formation . '", ';
		echo '"date_arret" : "' . date('d/m/Y', strtotime($resultat->date_arret)) . '", ';
		echo '"juridiction" : "' . $resultat->juridiction . '"';
		/*if (isset($resultats->highlighting))
			echo '"highlighting" : "' . JuricafArret::getExcerpt($resultat, $resultats->highlighting->{$resultat->id}) . '"';
		else
			echo '"highlighting" : "' . JuricafArret::getExcerpt($resultat) . '"';*/

	echo ' }';
	// Gestion de la derniere boucle
	$i++;
	if ($i < $total)
		echo ", ";
}
 
 // Fermeture de l'objet JSON
echo ' ] }';
 
 
} ?>