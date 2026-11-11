<?php if (!isset($pagina, $totalPaginas, $total, $porPagina, $offset, $rotuloRegistros)) { http_response_code(404); exit; } ?>
        <footer class="strain-rodape">
            <p>Mostrando <?= $total ? $offset + 1 : 0 ?> a <?= min($offset + $porPagina, $total) ?> de <?= $total ?> <?= $rotuloRegistros ?></p>
            <nav class="strain-paginacao" aria-label="Paginação">
                <?php if ($pagina > 1): ?><a href="?pagina=<?= $pagina - 1 ?>" aria-label="Página anterior"><?= icone('left') ?></a><?php else: ?><span aria-disabled="true" aria-label="Página anterior"><?= icone('left') ?></span><?php endif; ?>
                <?php
                $inicio = max(1, min($pagina - 2, $totalPaginas - 4));
                $fim = min($totalPaginas, $inicio + 4);
                if ($inicio > 1): ?><a href="?pagina=1">1</a><?php if ($inicio > 2): ?><span class="strain-reticencias">…</span><?php endif; endif;
                for ($p = $inicio; $p <= $fim; $p++): ?>
                    <a href="?pagina=<?= $p ?>" <?= $p === $pagina ? 'aria-current="page"' : '' ?> aria-label="Página <?= $p ?>"><?= $p ?></a>
                <?php endfor;
                if ($fim < $totalPaginas): if ($fim < $totalPaginas - 1): ?><span class="strain-reticencias">…</span><?php endif; ?><a href="?pagina=<?= $totalPaginas ?>"><?= $totalPaginas ?></a><?php endif; ?>
                <?php if ($pagina < $totalPaginas): ?><a href="?pagina=<?= $pagina + 1 ?>" aria-label="Próxima página"><?= icone('right') ?></a><?php else: ?><span aria-disabled="true" aria-label="Próxima página"><?= icone('right') ?></span><?php endif; ?>
            </nav>
        </footer>
