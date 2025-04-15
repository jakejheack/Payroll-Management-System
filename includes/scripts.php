<script src="js/sweetalert.min.js"></script>

<?php

            if(isset($_SESSION['status']))
            {
                ?>
                    <script>
                    swal({
                    title: "<?php echo $_SESSION['status']; ?>",
                    // text: "You clicked the button!",
                    icon: "success",
                    button: "OK",
                    });
                    </script>
                <?php
            unset($_SESSION['status']);
            }

            if(isset($_SESSION['del']))
            {
                ?>
                    <script>
                    swal({
                    title: "<?php echo $_SESSION['del']; ?>",
                    // text: "You clicked the button!",
                    // icon: "success",
                    button: "OK",
                    });
                    </script>
                <?php
            unset($_SESSION['del']);
            }

            if(isset($_SESSION['att']))
            {
                ?>
                    <script>
                    swal({
                    title: "<?php echo $_SESSION['att']; ?>",
                    // text: "You clicked the button!",
                    icon: "warning",
                    button: "OK",
                    });
                    </script>
                <?php
            unset($_SESSION['att']);
            }

            if(isset($_SESSION['check']))
            {
                ?>
                <script>
                        swal({
                        title: "<?php echo $_SESSION['check']; ?>",
                        // text: "",
                        icon: "warning",
                        button: "OK",
                        dangerMode: true,
                        });
                </script>
            <?php
                unset($_SESSION['check']);
            }

            if(isset($_SESSION['pw']))
            {
                ?>
                    <script>
                    swal({
                    title: "<?php echo $_SESSION['pw']; ?>",
                    // text: "You clicked the button!",
                    icon: "warning",
                    button: "OK",
                    });
                    </script>
                <?php
            unset($_SESSION['pw']);
            }


            if(isset($_SESSION['expired']))
            {
                ?>
                    <script>
                    swal({
                    title: "<?php echo $_SESSION['expired']; ?>",
                    // text: "You clicked the button!",
                    icon: "success",
                    button: "OK",
                    });
                    </script>
                <?php
        unset($_SESSION['expired']);
            }

        ?>

<script>
      let modalBtns = [...document.querySelectorAll(".button")];
      modalBtns.forEach(function(btn) {
        btn.onclick = function() {
          let modal = btn.getAttribute('data-modal');
          document.getElementById(modal)
            .style.display = "block";
        }
      });
      let closeBtns = [...document.querySelectorAll(".close")];
      closeBtns.forEach(function(btn) {
        btn.onclick = function() {
          let modal = btn.closest('.modal');
          modal.style.display = "none";
        }
      });
      window.onclick = function(event) {
        if(event.target.className === "modal") {
          event.target.style.display = "none";
        }
      }
</script>