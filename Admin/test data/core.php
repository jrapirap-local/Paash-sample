<script src="../assets/scripts/jquery-3.3.1.js" type="text/javascript"></script>
<script src="../assets/js/adminlte.js" type="text/javascript"></script>
<script src="../assets/js/sweetalert2.all.min.js" type="text/javascript"></script>
<script src="../assets/js/bootstrap.bundle.js" type="text/javascript"></script>
<script src="../assets/js/popper.min.js" type="text/javascript"></script>
<script src="../assets/js/overlayscrollbars.browser.es6.min.js" type="text/javascript"></script>
<script src="../assets/js/apexcharts.min.js" type="text/javascript"></script>
<script src="../assets/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="../assets/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="../assets/js/dataTables.select.min.js" type="text/javascript"></script>
<script src="../assets/js/jquery-ui.js" type="text/javascript"></script>
<script src="../assets/js/MultiSelect.js"></script>
<script src="../assets/global.js" type="text/javascript"></script>
<script src="../assets/js/moment.min.js"></script>


<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->

<script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
    scrollbarTheme: 'os-theme-light',
    scrollbarAutoHide: 'leave',
    scrollbarClickScroll: true,
    };
    document.addEventListener('DOMContentLoaded', function () {
    const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
    if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
        scrollbars: {
            theme: Default.scrollbarTheme,
            autoHide: Default.scrollbarAutoHide,
            clickScroll: Default.scrollbarClickScroll,
        },
        });
    }
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();       
    });
</script>