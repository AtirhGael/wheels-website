        </div><!-- /.adm-content -->

        <!-- Footer bar -->
        <footer class="adm-footer">
            <span>&copy; <?php echo date('Y'); ?> Elite BBS &mdash; Admin Panel</span>
            <span class="adm-footer-right">Logged in as <strong><?php echo isset($_admin_name) ? $_admin_name : 'Admin'; ?></strong></span>
        </footer>
    </div><!-- /.adm-main -->

</div><!-- /.adm-layout -->

<style>
.adm-footer {
    padding: 14px 32px;
    border-top: 1px solid #e8ecf0;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 12px;
    color: #aaa;
    font-family: 'Lato', sans-serif;
    flex-shrink: 0;
}
.adm-footer-right { color: #bbb; }
.adm-footer-right strong { color: #888; }
</style>

</body>
</html>
