<!doctype html>
<html lang="en">

<head>
  <?php $this->load->view('includes/header'); ?>
  <title>Codeigniter 3 CRUD Application</title>
</head>

<body>

  <div class="container">
    <div class="row">

      <div class="col-lg-12 my-5">
        <h2 class="text-center mb-3">Codeigniter 3 Blog</h2>
      </div>

      <div class="col-lg-12">

        <?php echo $this->session->flashdata('message'); ?>

        <div class="d-flex justify-content-between mb-3">
          <h4>Manage Posts</h4>
          <a href="<?= base_url('index.php/post/create') ?>" class="btn btn-success"> <i class="fas fa-plus"></i> Add New Post</a>
        </div>

        <table class="table table-bordered table-default">

          <thead class="thead-light">
            <tr>
              <th width="2%">#</th>
              <th width="25%">Title</th>
              <th width="53%">Description</th>
              <th width="20%">Action</th>
            </tr>
          </thead>

          <tbody>

            <?php $i = 1; foreach ($data as $post) { ?>

              <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $post->title; ?></td>
                <td><?php echo $post->description; ?></td>

                <td>
                  <a href="<?= base_url('index.php/post/edit/' . $post->id) ?>" class="btn btn-primary"> <i class="fas fa-edit"></i> Edit </a>
                  <a href="<?= base_url('index.php/post/delete/' . $post->id) ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?')"> <i class="fas fa-trash"></i> Delete </a>
                </td>

              </tr>

            <?php $i++; } ?>

          </tbody>

        </table>

      </div>
    </div>
  </div>



  <?php $this->load->view('includes/footer'); ?>

</body>

</html>