<?php $__env->startSection('content'); ?>
  <div id="loginSlider" class="carousel slide">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <!-- Select Company panel -->
        <div class="my-2">
          <h4 class="text-center mb-3">Hi, <?php echo e($user->first_name); ?>

            <small class="d-block text-muted">Select a Practice to continue</small>
          </h4>
          <form class="my-2 mb-0">
          <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
          <input type="hidden" name="org_id" id="org_id" value="<?php echo e($org_id); ?>">

           <input class="form-control" id="search-content" type="text" placeholder="Search..." placeholder="Search" onkeyup="search_value()">
          </form>
          <div class="list-group rounded mb-4"  id="secCompanyList" style="overflow-y: scroll;height: 200px;">
           <?php $__currentLoopData = $company_select; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button type="button" class="list-group-item" 
            onclick="companyRedirect(<?php echo e($company['id']); ?>)">
               <?php echo e($company['name']); ?>

              <small class="d-block text-muted">Last used: <?php echo e(date('M d, Y', strtotime($company['updated_at']))); ?></small>
            </button>
           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>

        <div style="margin-left:-25px;margin-right:-25px" class="py-3 text-center ">
          <?php if(Auth::user()->role_id == 1): ?>
            <a class="btn btn-outline-primary mb-1 shadow-sm" id="btnLogout" 
            href='<?php echo e(route("select_organization")); ?>' style="width: 71px">
              Back
            </a>&nbsp;&nbsp;&nbsp;&nbsp;
          <?php endif; ?>
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
  function companyRedirect(company_id){
    
    $.ajax({
      url: '<?php echo e(route("company_redirect")); ?>',
      type: 'GET',
      data: {'company_id' : company_id,
              'org_id' : $('#org_id').val() },
      success: function(response) {
        if(response.steps == 1){
          window.location.href = '<?php echo e(route("home")); ?>';
        }else{
          window.location.href = '<?php echo e(route("register")); ?>';
        }
       
      }
    });
  }

  function search_value(){
    var search_key = $('#search-content').val();
    var search_from = 'reg';

    $.ajax({
      url: '<?php echo e(route("search_company")); ?>',
      type: 'GET',
      data: {'search_key' : search_key, 'search_from' : search_from},
      success: function(response) {
        $('#secCompanyList').html(response)
      }
    });
  } 
</script>
<?php echo $__env->make('layouts.auth_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>