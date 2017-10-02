<style>
    table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #ccc;
    }

    table th, td {
        border: 1px solid #ccc;
    }

    table td.current {
        background-color: #27ae60;
        color: #fff;
        font-weight: bold;
    }

    .text-right {
        text-align: right;
    }
</style>

<h2>
    <?php echo htmlentities($webBenchmark->getResource()->getUrl()) ?>
    <small>loaded in <?php echo htmlentities($webBenchmark->getResource()->getLoadTimeFormatted()) ?></small>
</h2>

<table cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>URL</th>
            <th>Size</th>
            <th>Time</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($allResources as $compareResource) : ?>
            <tr>
                <td class="<?php echo $compareResource->isMain() ? ' current ' : '' ?>"><?php echo htmlentities($compareResource->getUrl()) ?></td>
                <td class="<?php echo $compareResource->isMain() ? ' current ' : '' ?> text-right"><?php echo htmlentities($compareResource->getSizeFormatted()) ?></td>
                <td class="<?php echo $compareResource->isMain() ? ' current ' : '' ?> text-right"><?php echo htmlentities($compareResource->getLoadTimeFormatted()) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
