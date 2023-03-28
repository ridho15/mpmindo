<!DOCTYPE html>
<html>
  <head>
      <meta charset="utf-8">
      <!-- Cross compatibility -->
      <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
      <title>Toko Buah</title>
      <meta name="description" content=""/>
  </head>
  <body>
    <button id="pay-button">Pay!</button>
    <div id="result-type"></div>
    <div id="result-data"></div>
	
	<script src="assets/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="https://app.sandbox.veritrans.co.id/snap/assets/snap.js" data-client-key="<?=$this->config->item('veritrans_client_key')?>"></script>
    <script type="text/javascript">
      var payButton = document.getElementById('pay-button');
      var resultType = document.getElementById('result-type');
      var resultData = document.getElementById('result-data');
      function changeResult(type,data){
        resultType.innerHTML = type;
        resultData.innerHTML = JSON.stringify(data);
      }
      payButton.onclick = function(){
        snap.pay('<?=$snap_token?>', {
          env: 'sandbox',
          onSuccess: function(result){changeResult('success', result)},
          onPending: function(result){changeResult('pending', result)},
          onError: function(result){changeResult('error', result)}
        });
      };
    </script>
  </body>
</html>