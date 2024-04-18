<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8" style="margin: 0 auto;">
            <nav aria-label="...">
                <ul class="pagination">
                    <?php if(isset($_GET['page'])){
                        $page = $_GET['page'];
                    }else{
                        $page=0;
                    }?>
                    <?php if ($page > 0) {?>
                        <li class="page-item">
                            <a href="index.php?accion=listado&page=<?= $page - 1 ?>">
                                <span class="page-link">Anterior</span>
                            </a>
                        </li>
                    <?php }?>
                    <?php if ($page >= 1) {?>
                        <li class="page-item">
                            <a href="index.php?accion=listado&page=<?= $page - 1 ?>">
                                <span class="page-link"><?= $page ?></span>
                            </a>
                        </li>
                    <?php }?>
                    <li class="page-item active">
                        <span class="page-link">
                            <?= $page + 1 ?>
                        </span>
                    </li>
                    <?php if ($resultModelo['paginas'] > ($resultModelo['offset'] + $resultModelo['longitudPag'])) {?>
                        <li class="page-item">
                            <a class="page-link" href="index.php?accion=listado&page=<?= $page + 1 ?>"><?= $page + 2 ?></a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="index.php?accion=listado&page=<?= $page + 1 ?>">Siguiente</a>
                        </li>
                    <?php }?>
                </ul>
            </nav>
        </div>
    </div>
</div>
