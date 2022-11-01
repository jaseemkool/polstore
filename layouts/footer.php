     </div>
     </div>
     <script src="libs/js/jquery.min.js"></script>
     <script src="libs/js/bootstrap.min.js"></script>
     <script src="libs/js/bootstrap-datepicker.min.js"></script>
     <script type="text/javascript" src="libs/js/functions.js"></script>
     <script type="text/javascript" charset="utf8" src="libs/js/jquery.dataTables.js"></script>
     <script src="libs/js/select2.js"></script>
     <script>
       $(document).ready(function() {
         $('.select2').select2();
       });
       $(document).ready(function() {
         $('#data_table').DataTable({
           scrollX: true,
         });
       });
     </script>

     <div class="footer navbar-fixed-bottom">
       <footer class="bg-light text-center text-lg-start">
         <!-- Copyright -->
         <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
           © 2022 Copyright:
           <a class="text-dark" href="https://poltech.herokuapp.com/">POLstore.</a>
           Designed, Developed and Maintained by Police Tele Sub Unit Malappuram
         </div>
         <!-- Copyright -->
       </footer>
     </div>
     
     </body>

     </html>

     <?php if (isset($db)) {
        $db->db_disconnect();
      } ?>