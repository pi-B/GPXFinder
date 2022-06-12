<?php
$dest = "agence.2bcl@gmail.com";
$sujet = "Ceci est un test";
$message = "Ceci est un test de la fonction mail()";
$header = "From: $dest";

/* test 1 */
$envoi = mail($dest, $sujet, $message, $header);

if ($envoi == true)
  echo "<p>Test 1 : la fonction mail() fonctionne. Un e-mail a ete envoye a l'adresse $dest.
  <br />S'il ne vous parvient pas, il y a probablement un blocage au niveau du serveur SMTP de l'hebergeur</p>";
else
  echo "<p>Test 1 : l'envoi par la fonction PHP mail() ne fonctionne pas ou est desactivee</p>";

/* test 2 */
echo "<p>Test 2 : Si bool(false) le mail ne part pas
<br />Si bool(true) l'e mail a bien ete envoye (donc il est bloque apres)</p>";
var_dump(mail($dest, $sujet, $message, $header));
?>