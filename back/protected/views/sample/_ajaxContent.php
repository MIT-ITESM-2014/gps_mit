
<?php
  if($step == 0)
  {
?>
    <script type="text/javascript">
      actualizarPaso(<?=$step?>);
    </script>
<?php
  }
  elseif($step == 1)
  {
    if($script == 1)
    {
      echo "actualizarPaso(".$step.")";
    }
    else{
  ?>
      <script type="text/javascript">
        actualizarPaso(<?=$step?>);
      </script>
<?php
    } 
  }
?>
