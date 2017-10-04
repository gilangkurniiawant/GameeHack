<?php

  $score = 396; //PUT YOUR DESIRED SCORE HERE
  $gamepage = "https://www.gameeapp.com/game/u0yXP5o-a0d48d0a1ae2ae6280e12c893814d3ebc39ee15f#tgShareScoreUrl=tg%3A%2F%2Fshare_game_score%3Fhash%3D37kGse67wMPEalbxnbjB6XZQRCnDy6S8Eiub1oV3Q_U"; //PUT GAME URL HERE


/**
DO NOT CHANGE ANYTHING BELOW
**/
  ob_start();

  function get_string_between($string, $start, $end){
      $string = ' ' . $string;
      $ini = strpos($string, $start);
      if ($ini == 0) return '';
      $ini += strlen($start);
      $len = strpos($string, $end, $ini) - $ini;
      return substr($string, $ini, $len);
  }


  $abspage = explode("#",$gamepage);
  $abspage = $abspage[0];
  $pagedata  = file_get_contents($gamepage);
  $dataid = get_string_between($pagedata,'data-id="','"');

 ?>
<script src="cryptojslib/rollups/aes.js"></script>
<script src="cryptojslib/components/enc-base64-min.js"></script>

<script>
  var CryptoJSAesJson={stringify:function(t){var i={ct:t.ciphertext.toString(CryptoJS.enc.Base64)};return t.iv&&(i.iv=t.iv.toString()),t.salt&&(i.s=t.salt.toString()),JSON.stringify(i)},parse:function(t){var i=JSON.parse(t),e=CryptoJS.lib.CipherParams.create({ciphertext:CryptoJS.enc.Base64.parse(i.ct)});return i.iv&&(e.iv=CryptoJS.enc.Hex.parse(i.iv)),i.s&&(e.salt=CryptoJS.enc.Hex.parse(i.s)),e}};

  n=CryptoJS.AES.encrypt(JSON.stringify({score:<?php echo $score; ?>,timestamp:(new Date).getTime()}),"<?php echo $dataid; ?>",{format:CryptoJSAesJson});
    //alert(n.toString().replaceAll('"','/"'));
    var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                alert(this.responseText);
            }
        };
        xmlhttp.open("GET", "?hash=" + n.toString(), true);
        xmlhttp.send();
</script>

<?php
if(isset($_GET['hash'])){

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://bots.gameeapp.com/set-web-score-qkfnsog26w7173c9pk7whg0iau7zwhdkfd7ft3tn');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_REFERER, $abspage);

    curl_setopt($ch, CURLOPT_POST, 1);
    $data['score'] = $score;
    $data['url'] = str_replace("https://www.gameeapp.com","",$abspage);
    $data['play_time'] = 2000;
    $data['hash'] = $_GET['hash'];
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.12; rv:55.0) Gecko/20300101 Firefox/55.0',
        'Accept: application/json, text/javascript, */*; q=0.01',
        'Accept-Language: Accept-Language',
        'Accept-Encoding: gzip, deflate, br',
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'Content-Length: '.strlen(json_encode($data,JSON_UNESCAPED_SLASHES)),
        'Origin: https://www.gameeapp.com',
        'Connection: keep-alive',
        ));

    curl_setopt($ch, CURLOPT_POSTFIELDS,

                json_encode($data,JSON_UNESCAPED_SLASHES));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $html = curl_exec($ch);
    curl_close ( $ch );
    ob_end_clean();
    $data = json_decode($html,true);

    echo $html;
}
?>
