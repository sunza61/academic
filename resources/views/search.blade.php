<html lang = "en">
   <head>
      <meta charset = "utf-8">
      <title>jQuery UI Autocomplete functionality</title>
      <link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"
         rel = "stylesheet">
      <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
      <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
      
      <!-- Javascript -->
      <script>
         $(function() {
            $( "#autocomplete-5" ).autocomplete({
               source: "http://172.28.80.250/sun/ldap/public/autocomplete",
               minLength: 2
            });
         });
      </script> 
   </head>
<body>
 
<!--
<div class="ui-widget">
<form action="qq" method="get">
<input name="aaaaa" id = "autocomplete-5">
<input  type="submit" value="OK">
</form>
      -->
      <form action="qq" method="get">
        <div class="form-group">
          <label class="control-label">ค้นหาหมายเลขครุภัณฑ์</label>
          <input maxlength="" name="aaaaa" id="autocomplete-5" type="text" required="required" class="form-control" placeholder="ระบุหมายเลขครุภัณฑ์" />
        </div>

        <button class="btn btn-primary nextBtn pull-right" type="submit">ต่อไป</button>
      </form>
</div>
 
 
</body>

</html>