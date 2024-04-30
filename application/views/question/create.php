<!doctype html>
<html lang="en">

<head>
  <?php $this->load->view('includes/header'); ?>
  <title>Tech Corner - Create new Question</title>
</head>

<body>

  <div class="container">
    <div class="row">

      <div class="col-lg-12 my-5">
        <h2 class="text-center mb-3">Tech Corner</h2>
      </div>

      <div class="col-lg-12">

        <div class="d-flex justify-content-between ">
          <h4>Askn a new question</h4>
          <a class="btn btn-warning" href="<?php echo base_url(); ?>"> <i class="fas fa-angle-left"></i> Back</a>
        </div>

        <form method="post" action="<?php echo base_url('index.php/question/store'); ?>">

          <div class="form-group">
            <label>Title</label>
            <input class="form-control" type="text" name="title">
          </div>

          <div class="form-group">
            <label>Description</label>
            <textarea class="form-control" name="description"></textarea>
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-success"> <i class="fas fa-check"></i> Submit </button>
          </div>

        </form>


      </div>
    </div>
  </div>

  <?php $this->load->view('includes/footer'); ?>

</body>

</html>