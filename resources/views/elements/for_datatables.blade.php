<!-- DataTables -->
<link rel="stylesheet" href="/assets/plugins/datatables/dataTables.bootstrap.css">
<!-- DataTables -->
<script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/plugins/datatables/dataTables.bootstrap.min.js"></script>

<script>
    $(function () {
        //DataTablesのデフォルトの設定を変更
        $.extend($.fn.dataTable.defaults, {
            language: {
                url: "http://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Japanese.json"
            }
        });
    });
</script>