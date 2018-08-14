<div class="panel panel-default panel-condensed">
    <div class="panel-heading">
        <strong>MAC 位址</strong>
    </div>
    <table id="mac-search" class="table table-hover table-condensed table-striped">
        <thead>
            <tr>
                <th data-column-id="hostname" data-order="asc">裝置</th>
                <th data-column-id="interface">介面</th>
                <th data-column-id="address" data-sortable="false">MAC 位址</th>
                <th data-column-id="description" data-sortable="false">說明</th></tr>
            </tr>
        </thead>
    </table>
</div>

<script>

var grid = $("#mac-search").bootgrid({
    ajax: true,
    rowCount: [50, 100, 250, -1],
    templates: {
        header: "<div id=\"{{ctx.id}}\" class=\"{{css.header}}\"><div class=\"row\">"+
                "<div class=\"col-sm-9 actionBar\"><span class=\"pull-left\">"+
                "<form method=\"post\" action=\"\" class=\"form-inline\" role=\"form\">"+
                "<div class=\"form-group\">"+
                "<select name=\"device_id\" id=\"device_id\" class=\"form-control input-sm\">"+
                "<option value=\"\">所有裝置</option>"+
<?php
$sql = 'SELECT `devices`.`device_id`,`hostname`, `sysName` FROM `devices`';

if (is_admin() === false && is_read() === false) {
    $sql    .= ' LEFT JOIN `devices_perms` AS `DP` ON `devices`.`device_id` = `DP`.`device_id`';
    $where  .= ' WHERE `DP`.`user_id`=?';
    $param[] = $_SESSION['user_id'];
}

$sql .= " $where ORDER BY `hostname`";
foreach (dbFetchRows($sql, $param) as $data) {
    echo '"<option value=\"'.$data['device_id'].'\""+';
    if ($data['device_id'] == $_POST['device_id']) {
        echo '" selected "+';
    }

    echo '">'.format_hostname($data).'</option>"+';
}
?>
               "</select>"+
               "</div>"+
               "<div class=\"form-group\">"+
               "<select name=\"interface\" id=\"interface\" class=\"form-control input-sm\">"+
               "<option value=\"\">所有介面</option>"+
               "<option value=\"Loopback%\" "+
<?php
if ($_POST['interface'] == 'Loopback%') {
    echo '" selected "+';
}
?>
               ">Loopbacks</option>"+
               "<option value=\"Vlan%\""+
<?php
if ($_POST['interface'] == 'Vlan%') {
    echo '" selected "+';
}
?>

               ">VLANs</option>"+
               "</select>"+
               "</div>"+
               "<div class=\"form-group\">"+
               "<input type=\"text\" name=\"address\" id=\"address\" value=\""+
<?php
echo '"'.$_POST['address'].'"+';
?>

               "\" class=\"form-control input-sm\" placeholder=\"MAC 位址\"/>"+
               "</div>"+
               "<button type=\"submit\" class=\"btn btn-default input-sm\">搜尋</button>"+
               "</form></span></div>"+
               "<div class=\"col-sm-3 actionBar\"><p class=\"{{css.actions}}\"></p></div></div></div>"
    },
    post: function ()
    {
        return {
            id: "address-search",
            search_type: "mac",
            device_id: '<?php echo htmlspecialchars($_POST['device_id']); ?>',
            interface: '<?php echo mres($_POST['interface']); ?>',
            address: '<?php echo mres($_POST['address']); ?>'
        };
    },
    url: "ajax_table.php"
});

</script>