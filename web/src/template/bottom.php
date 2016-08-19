<?php

while (($error = ErrorManager::pop()) !== null) {
    echo "<div class=\"alert alert-".($error->severity == ErrorManager::SEVERITY_ERROR ? "danger" : "warning")."\" role=\"alert\">
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
          <span aria-hidden=\"true\">&times;</span>
        </button>
        ".$error->msg."
    </div>";
}
?>

<footer class="footer">
    <div class="container">
        <table class="table borderless">
            <tr>
                <td><a href="/about"><?php echo I18n::get("about");?></a></td>
                <td><a href="/contact"><?php echo I18n::get("contact");?></a></td>
                <td><a href="/legal"><?php echo I18n::get("legal");?></a></td>
                <td><a href="/cookies"><?php echo I18n::get("cookies");?></a></td>
            </tr>
        </table>
    </div>
</footer>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="https://maxcdn.bootstrapcdn.com/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>