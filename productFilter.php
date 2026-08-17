<?php
$SQLstring = 'SELECT * FROM pyclass WHERE level=1 ORDER BY sort';
$pyclass01 = $link->query($SQLstring);
$i = 1;
?>
<div class="accordion" id="accordionExample">
    <?php while ($pyclass01_rows = $pyclass01->fetch()) {
        $i = $pyclass01_rows['classid']; ?>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne<?php echo $i; ?>" aria-expanded="true" aria-controls="collapseOne<?php echo $i; ?>">
                    <i class="fas <?php echo $pyclass01_rows['fonticon']; ?> fa-lg fa-fw"></i><?php echo $pyclass01_rows['cname']; ?>
                </button>
            </h2>
            <?php
            if (isset($_GET['p_id'])) {
                $SQLstring = sprintf("SELECT uplink FROM pyclass,product WHERE pyclass.classid=product.classid AND p_id=%d", $_GET['p_id']);
                $classid_rs = $link->query($SQLstring);
                $data = $classid_rs->fetch();
                $ladder = $data['uplink'];
            } elseif (isset($_GET['level']) && $_GET['level'] == 1) {
                $ladder = $_GET['classid'];
            } elseif (isset($_GET['classid'])) {
                $SQLstring = "SELECT uplink FROM pyclass where level=2 AND classid=" . $_GET['classid'];
                $classid_rs = $link->query($SQLstring);
                $data = $classid_rs->fetch();
                $ladder = $data['uplink'];
            } else {
                $ladder = 1;
            }
            $SQLstring = sprintf("SELECT * FROM pyclass WHERE level=2 AND uplink=%d ORDER BY sort", $pyclass01_rows['classid']);
            $pyclass02 = $link->query($SQLstring);
            ?>
            <div id="collapseOne<?php echo $i; ?>" class="accordion-collapse collapse <?php echo ($i == $ladder) ? 'show' : ''; ?>" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <table class="table">
                        <tbody>
                            <?php while ($pyclass02_rows = $pyclass02->fetch()) { ?>
                                <tr>
                                    <td><em class="fas <?php echo $pyclass02_rows['fonticon']; ?> fa-fw"></em><a href="productList.php?classid=<?php echo $pyclass02_rows['classid']; ?>"> <?php echo $pyclass02_rows['cname']; ?></a></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php $i++;
    } ?>

</div>