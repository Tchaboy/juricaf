<?php

$natureConstit = array(
      "QPC" => "Question prioritaire de constitutionnalité",
      "DC" => "Contrôle de constitutionnalité des lois ordinaires, lois organiques, des traités, des règlements des Assemblées",
      "LP" => "Contrôle de constitutionnalité des lois du pays de Nouvelle-Calédonie",
      "L" => "Déclassements de textes législatifs au rang réglementaire",
      "FNR" => "Fins de non-recevoir",
      "LOM" => "Répartitions des compétences entre l'État et certaines collectivités d'outre-mer",
      "AN" => "Élections à l'Assemblée nationale",
      "SEN" => "Élections au Sénat",
      "PDR" => "Élection présidentielle",
      "REF" => "Référendums",
      "ELEC" => "Divers élections : observations",
      "ELECT" => "Divers élections : observations",
      "D" => "Déchéance de parlementaires",
      "I" => "Incompatibilité des parlementaires",
      "AR16" => "Article 16 de la Constitution (pouvoirs exceptionnels du Président de la République)",
      "NOM" => "Nomination des membres",
      "RAPP" => "Nomination des rapporteurs-adjoints",
      "ORGA" => "Décision d'organisation du Conseil constitutionnel",
      "AUTR" => "Autres décisions"
      );
/*
function decrap($key) {
  $crap = array("value", "storage", "requiredProperties", "modified", "newDocument", "newAttachments", "escapingMethod");
  foreach ($crap as $value) {
    if(strpos($key, $value) !== false) { $key = $value; }
  }
  return $key;
}

function extraSub($field) {
  if (isset($field)) {
    $field = (array)$field;
    echo '<ul>';
    foreach ($field as $key => $value)
    {
      if (is_array($value) || is_object($value)) {
        if(is_object($value)) { $value = (array)$value; } ;
        if (!in_array($key, array('texte_arret', '_attachments', '@attributes', '_rev'))) {
          echo '<li><strong>'.decrap($key).' : </strong>';
          $field[$key] = extraSub($value);
        }
      }
      else {
        if (!in_array($key, array('texte_arret', '_attachments', '@attributes', '_rev'))) {
          echo '<li><strong>'.decrap($key).' : </strong>'.$value.'</li>';
        }
      }
    }
    echo '</ul>';
  }
}
*/

function replaceAccents($string){
  return strtr($string, 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}

function replaceBlank($str) {
  return str_replace (' ', '_', $str);
}

if(isset($document->references)) {
  foreach ($document->getReferences() as $values) {
    $i = 0;
    if(is_array($values) || is_object($values)) {
      foreach($values as $keys => $value) {
        if(is_array($value) || is_object($value)) {
          if(isset($value['type'])) {
            foreach ($value as $key => $vals) {
              if($vals !== $value['type']) {
                $references[$value['type']][$i][$key] = $vals;
              }
            }
          }
        }
        else {
          if($value !== $values['type']) {
            $references[$values['type']][0][$keys] = $value;
          }
        }
      $i++;
      }
    }
  }
}
?>
  <div class="arret">
    <h1><?php echo '<img class="drapeau" src="/images/drapeaux/'.replaceBlank($document->pays).'.png" alt="§" /> '.$document->titre; ?></h1>
    <?php
    if (isset($document->titre_supplementaire)) {
      echo '<h2>'.$document->titre_supplementaire.'</h2>';
    }
    if (isset($document->section)) {
      echo '<h3>'.$document->section.'</h3>';
    }
    if (isset($document->sens_arret)) {
      echo 'Sens de l\'arrêt : <em>'.$document->sens_arret.'</em><br />';
    }
    if (isset($document->type_affaire)) {
      if(isset($natureConstit[$document->type_affaire])) {
        echo 'Type d\'affaire : <em>'.$natureConstit[$document->type_affaire].'</em><br />';
      }
      else {
        echo 'Type d\'affaire : <em>'.$document->type_affaire.'</em><br />';
      }
    }
    if (isset($document->type_recours)) {
      echo 'Type de recours : <em>'.$document->type_recours.'</em><br />';
    }

    $description = '';
    $keywords = '';

    if (isset($document->analyses)) {
      echo '<hr />';
      echo '<h3>Analyses : </h3>';
      if (isset($document->analyses['analyse'])) {
        foreach($document->analyses['analyse'] as $key => $values) {
          //echo '<div>';
          if(is_array($values) || is_object($values)) {
            foreach($values as $key => $value) {
              echo '<blockquote>';
              if(strpos($key, 'titre') !== false) { echo '<h2>'; $description .= ' '.$value; $keywords .= ' '.$value;}
              else { echo '<p>'; $description .= ' '.$value; }
              echo $value;
              if(strpos($key, 'titre') !== false) { echo '</h2>'; }
              else { echo '</p>'; }
              echo '</blockquote>';
            }
          }
          else {
            echo '<blockquote><p>';
              if(strpos($key, 'titre') !== false) { echo '<h2>'; }
              echo $values;
              if(strpos($key, 'titre') !== false) { echo '</h2>'; }
              echo '</p></blockquote>';
          }
        }
        if(isset($references['CITATION_ANALYSE'])) {
        foreach($references['CITATION_ANALYSE'] as $value) {
          echo '<blockquote><p><em>Références :</em><br />';
          if(isset($value['nature'], $value['date'], $value['titre'])) {
            $titre = $value['nature'].' du '.$value['date'].' sur '.$value['titre'];
          }
          else { $titre = $value['titre']; }
          if(isset($value['url'])) {
            echo '<a href="'.$value['url'].'">'.$titre.'</a><br />';
          }
          else { echo $titre.'<br />'; }
          echo '</p></blockquote>';
        }
      }
        //echo '</div>';
      }
    }

    if (isset($document->saisines)) {
      echo '<hr />';
      echo '<h3>Analyses : </h3>';
      if (isset($document->saisines['saisine'])) {
        foreach($document->saisines['saisine'] as $key => $values) {
          echo '<div>';
          if(is_array($values) || is_object($values)) {
            foreach($values as $key => $value) {
              echo '<blockquote><p>';
              echo $value;
              echo '</p></blockquote>';
            }
          }
          else {
            echo '<blockquote><p>';
              echo $values;
              echo '</p></blockquote>';
          }
        }
        echo '</div>';
      }
    }

    if (isset($document->parties)) {
      echo '<hr />';
      echo '<h3>Parties : </h3>';
      if (isset($document->parties['demandeurs'])) {
        echo 'Demandeurs : ';
        $sep = ''; $i = 1;
        foreach($document->parties['demandeurs'] as $value) {
          if($i > 1) { $sep = ', '; }
          echo '<em>'.$sep.$value.'</em>'; $i++;
        }
        echo '<br />';
      }
      if (isset($document->parties['defendeurs'])) {
        echo 'Défendeurs : ';
        $sep = ''; $i = 1;
        foreach($document->parties['defendeurs'] as $value) {
          if($i > 1) { $sep = ', '; }
          echo '<em>'.$sep.$value.'</em>'; $i++;
        }
        echo '<br />';
      }
    }

    echo '<hr />';

    if($document->pays == "Madagascar" && $document->juridiction == "Cour suprême" && trim($document->texte_arret) == "En haut a droite, cliquez sur PDF pour visualiser le fac-simile de la décision") {
    ?>
    <object data="http://www.juricaf.org/Juricaf/Arrets/Madagascar/Cour%20supr%C3%AAme/<?php echo $document->juricaf_id; ?>.PDF" type="application/pdf" width="100%" height="1000" navpanes="0" statusbar="0" messages="0">
    Cette décision est disponible au format pdf : <a href="http://www.juricaf.org/Juricaf/Arrets/Madagascar/Cour%20supr%C3%AAme/<?php echo $document->juricaf_id; ?>.PDF"><?php echo $document->titre; ?></a>
    </object>
    <?php
    }
    else {
      echo '<h3>Texte : </h3>';
      echo '<p>'.preg_replace('/\n/', '<br />', preg_replace ('/\n\n/', '</p><p>', $document->texte_arret)).'</p>';
    }

    if(isset($references['CITATION_ARRET']) || isset($references['SOURCE'])) {
      echo '<p><em>Références : </em><br />';
      if(isset($references['CITATION_ARRET'])) {
        foreach($references['CITATION_ARRET'] as $value) {
          if(isset($value['nature'], $value['date'], $value['titre'])) {
            $titre = $value['nature'].' du '.$value['date'].' sur '.$value['titre'];
          }
          else { $titre = $value['titre']; }
          if(isset($value['url'])) {
            echo '<a href="'.$value['url'].'">'.$titre.'</a><br />';
          }
          else { echo $titre.'<br />'; }
        }
      }

      if(isset($references['SOURCE'])) {
        foreach($references['SOURCE'] as $value) {
          if(isset($value['nature'], $value['date'], $value['titre'])) {
            $titre = $value['nature'].' du '.$value['date'].' sur '.$value['titre'];
          }
          else { $titre = $value['titre']; }
          if(isset($value['url'])) {
            echo '<a href="'.$value['url'].'">'.$titre.'</a><br />';
          }
          else { echo $titre.'<br />'; }
        }
      }
      echo '</p>';
    }

    if(isset($document->nor) || isset($document->ecli) || isset($document->numeros_affaires)) {
      echo '<hr />';
      if (isset($document->nor)) {
        echo 'Numéro NOR : <em>'.$document->nor.'</em><br />';
      }
      if (isset($document->ecli)) {
        echo 'Numéro ECLI : <em>'.$document->ecli.'</em><br />';
      }
      if (isset($document->numeros_affaires)) {
        $sep = ''; $i = 0;
        foreach($document->numeros_affaires as $values) {
          if(is_array($values) || is_object($values)) {
            foreach($values as $value) {
              if($i > 0) { $sep = ', '; }
              $numeros = $sep.$value; $i++;
            }
          }
          else {
            if($i > 0) { $sep = ', '; }
            $numeros = $sep.$values; $i++;
          }
        }
        if($i > 1) { $s = 's'; } else { $s = ''; }
        echo 'Numéro d\'affaire'.$s.' : <em>'.$numeros.'</em><br />';
      }
    }

    if(isset($references['PUBLICATION'])) {
      echo '<hr /><h3>Publications :</h3>';
        foreach($references['PUBLICATION'] as $value) {
          if(isset($value['url'])) {
            echo '<a href="'.htmlentities($value['url']).'">'.$value['titre'].'</a><br />';
          }
          else { echo $value['titre'].'<br />'; }
        }
      }

    if(isset($document->president) || isset($document->avocat_gl) || isset($document->rapporteur) || isset($document->commissaire_gvt) || isset($document->avocats)) {
      echo '<hr /><h3>Composition du Tribunal :</h3>';
      if (isset($document->president)) {
        echo 'Président : <em>'.$document->president.'</em><br />';
      }
      if (isset($document->avocat_gl)) {
        echo 'Avocat général : <em>'.$document->avocat_gl.'</em><br />';
      }
      if (isset($document->rapporteur)) {
        echo 'Rapporteur : <em>'.$document->rapporteur.'</em><br />';
      }
      if (isset($document->commissaire_gvt)) {
        echo 'Commissaire gouvernement : <em>'.$document->commissaire_gvt.'</em><br />';
      }
      if (isset($document->avocats)) {
        echo 'Avocats : <em>'.$document->avocats.'</em><br />';
      }
    }

    if (isset($document->fonds_documentaire)) {
      echo '<hr /><p>Origine : <em>'.$document->fonds_documentaire.'</em></p><br />';
    }
    ?>
  </div>
  <div class="extra">
  <?php
  /*
  echo '<h3>Extras (affichage brut des champs disponibles)</h3>';
  echo '<p>';
  echo extraSub($document);
  echo '</p>';
  */
  ?>
  <a href="/couchdb/_utils/document.html?<?php echo sfConfig::get('app_couchdb_database');?>/<?php echo $document->_id; ?>">Admin</a>
  </div>
<div class="download">
<?php // echo link_to('Télécharger au format juricaf', '@arretxml?id='.$document->_id); ?>
</div>
<?php
///// METAS /////
// CLASSIQUES //
/*
- Description : les sommaires
- Mots-clés: Mettre les mots clés des titres principaux et secondaires
 * */
$sf_response->setTitle($document->titre.'- Juricaf');
$sf_response->addMeta('Description', $description);
$sf_response->addMeta('Keywords', $keywords);

// ECLI //

$code_pays_euro = array(
      "Belgique" => "BE",
      "Bulgarie" => "BG",
      "République tchèque" => "CZ",
      "Danemark" => "DK",
      "Allemagne" => "DE",
      "Estonie" => "EE",
      "Irlande" => "IE",
      "Grèce" => "EL",
      "Espagne" => "ES",
      "France" => "FR",
      "Italie" => "IT",
      "Chypre" => "CY",
      "Lettonie" => "LV",
      "Lituanie" => "LT",
      "Luxembourg" => "LU",
      "Hongrie" => "HU",
      "Malte" => "MT",
      "Pays-Bas" => "NL",
      "Autriche" => "AT",
      "Pologne" => "PL",
      "Portugal" => "PT",
      "Roumanie" => "RO",
      "Slovénie" => "SI",
      "Slovaquie" => "SK",
      "Finlande" => "FI",
      "Suède" => "SE",
      "Royaume-Uni" => "UK"
      );
// http://publications.europa.eu/code/fr/fr-370100.htm

$abbr_juridiction = array(
      "Haute cour de cassation et de justice" => "HCCJ", // Roumanie
      "Cour supérieure de justice" => "CSJ", // Luxembourg
      "Cour constitutionnelle" => "CC", // Luxembourg
      "Cour suprême" => "CS", // Hongrie
      "Tribunal des conflits" => "TC",
      "Cour de discipline budgétaire et financière" => "CDBF",
      "Cour de cassation" => "CASS",
      "Conseil d'état" => "CE",
      "Conseil constitutionnel" => "CC",
      "Cour suprême de cassation" => "CSC", // Bulgarie
      "Cour d'arbitrage" => "CA", // Belgique
      "Cour de justice de l'union européenne" => "CJUE"
      );

if (array_key_exists($document->pays, $code_pays_euro) && array_key_exists($document->juridiction, $abbr_juridiction)) {
  $creator = $document->juridiction;
  if(isset($document->section)) { $creator .= ' '.$document->section; }

  $contributors = '';

  if(isset($document->president) || isset($document->avocat_gl) || isset($document->rapporteur) || isset($document->commissaire_gvt) || isset($document->avocats)) {
    if (isset($document->president)) {
      $contributors .= 'Président : '.$document->president.' ; ';
    }
    if (isset($document->avocat_gl)) {
      $contributors .= 'Avocat général : '.$document->avocat_gl.' ; ';
    }
    if (isset($document->rapporteur)) {
      $contributors .= 'Rapporteur : '.$document->rapporteur.' ; ';
    }
    if (isset($document->commissaire_gvt)) {
      $contributors .= 'Commissaire gouvernement : '.$document->commissaire_gvt.' ; ';
    }
    if (isset($document->avocats)) {
      $contributors .= 'Avocats : '.$document->avocats.'.';
    }
    $contrib = true;
  }

  //$sf_response->auto_discovery_link_tag(false, 'http://purl.org/dc/elements/1.1/', 'rel="schema.DC"');
  //<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />
  //<link rel="schema.DCTERMS" href="http://purl.org/dc/terms/" />

  // Obligatoire
  $sf_response->addMeta('DC.format', 'text/html; charset=utf-8', false, false, false);
  $sf_response->addMeta('DC.identifier', $sf_request->getUri(), false, false, false);
  $sf_response->addMeta('DC.isVersionOf', 'ECLI:'.$code_pays_euro[$document->pays].':'.$abbr_juridiction[$document->juridiction].':'.substr($document->date_arret, 0, 4).':'.$document->num_arret, false, false, false);
  $sf_response->addMeta('DC.creator', $creator, false, false, false);
  $sf_response->addMeta('DC.coverage', $document->pays.' '.$creator, false, false, false);
  $sf_response->addMeta('DC.date', $document->date_arret, false, false, false);
  $sf_response->addMeta('DC.language', 'FR', false, false, false);
  $sf_response->addMeta('DC.publisher', 'AHJUCAF', false, false, false);
  $sf_response->addMeta('DC.accessRights', 'public', false, false, false);
  $sf_response->addMeta('DC.type', 'judicial decision', false, false, false);

  // Facultatif
  // $sf_response->addMeta('DC.title', 'Noms des parties', false, false, false);
  if(isset($document->type_affaire)) {
  $sf_response->addMeta('DC.subject', 'Affaire '.strtolower($document->type_affaire), false, false, false);
  }
  //$sf_response->addMeta('DC.abstract', 'Présentation, résumé de l’affaire', false, false, false);
  $sf_response->addMeta('DC.description', 'Mots-clés', false, false, false);
  if(isset($contrib)) {
  $sf_response->addMeta('DC.contributor', $contributors, false, false, false);
  }
  //$sf_response->addMeta('DC.issued', 'Date de publication', false, false, false);
  $sf_response->addMeta('DC.references', 'Références à d’autres documents juridiques + urls', false, false, false);
  // $sf_response->addMeta('DC.isReplacedBy', 'En cas de renumérotation', false, false, false);
}
?>
