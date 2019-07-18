<?php $__env->startSection('content'); ?>
  <div id="loginSlider" class="carousel slide">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <!-- Select Company panel -->
        <div class="my-2">
          <h4 class="text-center mb-3">
            <small class="d-block text-muted">Select an organization</small>
          </h4>
          <form class="my-2 mb-0">
            <?php echo csrf_field(); ?>
           <input class="form-control" id="search-content" type="text" placeholder="Search..." placeholder="Search" onkeyup="search_value()">
          </form>
          <div class="list-group rounded mb-4"  id="secCompanyList" style="overflow-y: scroll;height: 200px;">
           <?php $__currentLoopData = $organization_select; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $organization): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a type="button" class="list-group-item" 
            href="<?php echo e(route('select_company').'?org_id='.$organization['id']); ?>" style="text-align: center;text-decoration: none;">
               <?php echo e($organization['company_name']); ?>

            </a>
           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>

        <div style="margin-left:-25px;margin-right:-25px" class="py-3 text-center ">
          <button class="btn btn-outline-primary mb-1 shadow-sm" id="btnLogout" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            Logout
          </button>
           <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
              <?php echo csrf_field(); ?>
            </form>
        </div>

        <div style="margin-left:-25px;margin-right:-25px" class="py-3 text-center d-none">
          <button class="btn btn-light rounded mb-1 shadow-sm">
            Start a new company subscription
          </button>
        </div>
      </div>
    </div>
  </div>

<?php $__env->stopSection(); ?>
<script type="text/javascript">
  function search_value(){
    var search_key = $('#search-content').val();
    var search_from = 'reg';

    $.ajax({
      url: '<?php echo e(route("search_organization")); ?>',
      type: 'GET',
      data: {'search_key' : search_key, 'search_from' : search_from},
      success: function(response) {
        $('#secCompanyList').html(response)
      }
    });
  } 
</script>
<?php echo $__env->make('layouts.auth_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>