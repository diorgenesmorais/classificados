<div class="carousel slide" data-ride="carousel" id="myCarousel">
  <div class="carousel-inner" role="listbox">
    <?php foreach($info['fotos'] as $key => $foto): ?>
      <div class="item <?php echo ($key==0)?'active':''; ?>">
        <img class="iphoto" src="<?php echo FILE_LOCATION.$foto['url']; ?>" alt="<?php echo $foto['alt']; ?>">
      </div>
    <?php endforeach; ?>
  </div>
  <a class="carousel-control left" role="button" data-slide="prev" href="#myCarousel"><span><</span></a>
  <a class="carousel-control right" role="button" data-slide="next" href="#myCarousel"><span>></span></a>
</div>
