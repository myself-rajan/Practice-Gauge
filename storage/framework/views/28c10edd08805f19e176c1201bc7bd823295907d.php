<?php $__env->startSection('content'); ?>
    <div class="workspace p-4" id="workspace" style="transition: all 0.3s ease 0s; transform: none; opacity: 1;"><div id="navData" data-title="Accounts Mapping - Practice Gauge" data-page-header="Accounts Mapping"></div>
    <link href="<?php echo e(asset('css/app_modules/connected.list.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(asset('js/views/company_industry.js')); ?>"></script>
    <div class="card bg-white shadow-sm rounded border-0">
      <div class="card-body">
        <input class="form-control form-control-sm float-right w-auto" placeholder="Type here to search categories" id="txtCatSearchKey">
        <div class="text-muted"><i class="fas fa-info-circle mr-1 animated flash text-warning " style="animation-iteration-count:3"></i>
          Drag the accounts
          you require into the
          categories you choose.</div>
      </div>

      <div class="card-body border-top">
        <div class="connected-list">
            <div class="row">
                <div class="col-4 col-xl-3">
                  <strong class="mb-1 d-block clearfix">Available Accounts
                  </strong><meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
              
                  <ul id="clVerticals" class="verticals-sortable list-group connected-available ui-sortable">
                    <?php if(isset($accountsList)): ?>
                    <?php $__currentLoopData = $accountsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 

                      <li class="list-group-item ui-sortable-handle" data-account_id="<?php echo e($row->id); ?>" data-values="<?php echo e($row->values); ?>"><?php echo e($row->account_num); ?>  <?php echo e($row->account_name); ?> <button class="float-right btn-move"><i class="fas fa-chevron-right"></i></li><!-- [account_id => <?php echo e($row->id); ?> , values => <?php echo e($row->values); ?>] -->
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                     Not record found
                    <?php endif; ?>
                  </ul>
                </div>
                <div class="col-8 col-xl-9 connected-list-group">
                  <div class="row">
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                      <?php ($displayStyle = "display:block"); ?>
                      <?php if($row->id == 14 || $row->id == 6): ?> <!-- Net Operating Income -->
                        <?php ($displayStyle = "display:none"); ?>
                      <?php endif; ?>

                        <?php ($labelCategory = ''); ?>
                        <?php if($row->parent_category_id == 6): ?>
                          <?php ($labelCategory = 'Employee Costs: '); ?>
                        <?php endif; ?>
                        <div class="col-5 col-lg-4" style="<?php echo e($displayStyle); ?>">
                          <strong class="mb-1 clearfix d-block"><?php echo e($labelCategory); ?><?php echo e($row->name); ?>

                          </strong>
                          <ul id="listCat<?php echo e($row->id); ?>" data-category_id="<?php echo e($row->id); ?>" class="accounts_list verticals-sortable list-group connected-selected list-group-sm ui-sortable">
                              <?php $__currentLoopData = $mappingList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mappingKey => $mappingRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                  <?php if($mappingRow->category_id == $row->id): ?>
                                      <li class="list-group-item ui-sortable-handle" data-account_id="<?php echo e($mappingRow->accounts_id); ?>" data-values="<?php echo e($mappingRow->values); ?>"> <?php echo e($mappingRow->account_name); ?> <button class="float-right btn-move"><i class="fas fa-chevron-right"></i></li>
                                        <!-- [values => <?php echo e($mappingRow->values); ?> , account_id => <?php echo e($mappingRow->account_id); ?> ] -->
                                  <?php endif; ?>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </ul>
                        </div>
                      
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </div>

                </div>
            </div>
        </div>
      </div>

      <div class="card-footer text-right">
        <button class="btn btn-sm btn-success px-3" id="btnSaveVerticals">Save</button>
      </div>
      </div>
    </div>

    <script type="text/javascript">
      $( document ).ready(function() {
        window.workspaceScript.onLoad();
      });

        $('#btnSaveVerticals,#btnSaveModels').click(function () {
          saveMapAccount();
          /*flag = 0;
          $( "#clVerticals li" ).each(function( index ) {
            if($( this ).data('values') != '') {
              flag = flag+1;
            }
          });

          if(flag > 0) {
              strawberry.toast.show("Please map account with category", "warning");
              strawberry.toast.hide();
              return false;
          } else {
              saveMapAccount();
          }*/
        });

        function saveMapAccount() {
          var _btn = $('#btnSaveVerticals,#btnSaveModels');
          _btn.text('Saving...');
          _btn.addClass('btn-processing');
          _btn.prop('disabled', true);

          _btn.popover({
              html: true,
              content: "<i class=fas fa-check-circle mr-2'></i> Information saved successfully",
              trigger: 'focus',
              placement: 'top',
              template: '<div class="popover border-success" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
            }).popover('show');

          /*var categoryIdArr = new Array();
          $('.accounts_list').each(function () {
            var accountIdArr = new Array();
            var id = $(this).data('category_id');

            $('#listCat'+id+' li').each(function () {
              //console.log(id+' '+$(this).data('account_id'));
              accountIdArr.push($(this).data('account_id'));
            });
            categoryIdArr.push(accountIdArr);
          });
          console.log(categoryIdArr);*/

          var categoryIdArr = new Array(); accountIdArr=[],i=0;
          $('.accounts_list').each(function () {
            var cat_id = $(this).data('category_id');

            $(this).find("li").each(function () {
              accountIdArr[i] = $(this).data('account_id');
              i++;
            });
            categoryIdArr[cat_id] = accountIdArr;
            accountIdArr=[],i=0;
          });
          console.log(categoryIdArr);

          $.ajax({
            url: '<?php echo e(route("save_account_mapping")); ?>',
            type: 'POST',
            data: { 
              "_token": "<?php echo e(csrf_token()); ?>",
              'categoryIdArr' : categoryIdArr
            },
            success: function (result) {   
              strawberry.toast.show("Information saved successfully", "success");
              _btn.text('Save');
              _btn.removeClass('btn-processing');
              _btn.prop('disabled', false);

              _btn.popover('dispose');
              strawberry.toast.hide();
                
            }
          });
        }

    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>