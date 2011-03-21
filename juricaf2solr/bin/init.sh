#!/bin/bash

TMPFILE=/tmp/$$.json
DB=http://localhost:5984/ahjucaf

if test "$1" ; then  
echo "DELETING: please confirm "
read AA;
curl -X DELETE $DB
curl -X PUT $DB
fi

echo "Creating map/reduce stats";

curl -X DELETE $DB/_design/stats?rev=$(curl --stderr /dev/null $DB/_design/stats | sed 's/.*_rev":"//' | sed 's/",".*//' 2> /dev/null)

cat <<EOF > $TMPFILE
{
  "_id":"_design/stats",
  "language": "javascript",
  "views":
  {
    "pays_juridiction": {
      "map": "function(doc) { if (doc.type == 'arret' && doc.pays && doc.juridiction)  emit([doc.pays,doc.juridiction], 1);}",
      "reduce": "function(keys, values) { return sum(values) }"
    },
    "attributs": {
      "map": "function(doc) {if (doc.type == 'arret') for (var attr in doc) if (attr != 'unnamed') emit(attr, 1); }",
      "reduce": "function(keys, values) { return sum(values) }"
    }
  }
}
EOF

curl -X PUT -d "@$TMPFILE" $DB/_design/stats

echo "Creating map errors";

cat <<EOF > $TMPFILE
{
  "_id":"_design/errors",
  "language": "javascript",
  "views":
  {
    "pas_de_texte_arret": {
      "map": "function(doc) { if (doc.type == 'arret' && (!doc.texte_arret || doc.texte_arret.length < 1))  emit(doc._id, 1); }"
    },
    "num_arret_trop_gros": {
      "map": "function(doc) { if (doc.type == 'arret' && doc.num_arret.length > 20)  emit(doc._id, 1); }"
    }
  }
}
EOF

curl -X DELETE $DB/_design/errors?rev=$(curl --stderr /dev/null $DB/_design/errors | sed 's/.*_rev":"//' | sed 's/",".*//')

curl -X PUT -d "@$TMPFILE" $DB/_design/errors

rm $TMPFILE