
<?php if ($session->isUserLoggedIn(true)) : ?>

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.2.0
    </div>
    <strong>Copyright &copy; 2022 <a href="#">POLstore</a>.</strong> All rights reserved. 
           Designed, Developed and Maintained by Police Tele Sub Unit Malappuram
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="libs/js/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="libs/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="libs/js/adminlte.min.js"></script>


     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
     <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>

     <script type="text/javascript" src="libs/js/functions.js"></script>
     <script src="libs/js/select2.js"></script>
     <script>
       $(document).ready(function() {
         $('.select2').select2();
       });
     </script>

<div class="navbar navbar-fixed-bottom" style="opacity: 0.5;">
       <footer class="bg-light text-center text-lg-start">
         <!-- Copyright -->
         <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
           © 2022 Copyright:
           <a class="text-dark" href="https://poltech.herokuapp.com/">POLstore.</a>
         </div>
         <!-- Copyright -->
       </footer>
     </div>
</body>

</html>

<?php endif; ?>

     <?php if (isset($db)) {
        $db->db_disconnect();
      } ?>
