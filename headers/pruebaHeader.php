<?php
echo "<pre>";
var_dump(apache_request_headers());
echo "</pre>";
header("Refresh: 5; url=https://www.google.com");
echo "Lo que se busca no existe, redireccionando a Google en 5 segundos.";
?>