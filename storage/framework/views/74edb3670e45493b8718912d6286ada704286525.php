<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densityDpi=device-dpi" />
    <title> Blogs &ndash; Shivveda Private Limited</title>
    <meta name="description" content="Shivveda. This Ayurvedic powerhouse has been on a mission to bring age-old remedies to the forefront of holistic well-being. Two of their exceptional products, Kaya Trim and Madhu Ras, have garnered widespread acclaim for their effectiveness in promoting weight loss and managing sugar levels.">
    <meta property="og:site_name" content="Shivveda Private Limited">
    <meta property="og:url" content="https://www.shivveda.com/">
    <meta property="og:title" content="Shivveda">
    <meta property="og:type" content="website">
    <meta property="og:description" content="Shivveda. This Ayurvedic powerhouse has been on a mission to bring age-old remedies to the forefront of holistic well-being. Two of their exceptional products, Kaya Trim and Madhu Ras, have garnered widespread acclaim for their effectiveness in promoting weight loss and managing sugar levels."><meta property="og:image" content="http://www.shivveda.com/cdn/shop/files/Slide_Kaya_Trim_Final_001_06ec23c2-b9d5-4350-8d49-d86f4edca9c8.jpg?v=1720183897">
      <meta property="og:image:secure_url" content="https://www.shivveda.com/cdn/shop/files/Slide_Kaya_Trim_Final_001_06ec23c2-b9d5-4350-8d49-d86f4edca9c8.jpg?v=1720183897">
      <meta property="og:image:width" content="1903">
      <meta property="og:image:height" content="1080"><meta name="twitter:site" content="@#"><meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Shivveda">
    <meta name="twitter:description" content="Shivveda. This Ayurvedic powerhouse has been on a mission to bring age-old remedies to the forefront of holistic well-being. Two of their exceptional products, Kaya Trim and Madhu Ras, have garnered widespread acclaim for their effectiveness in promoting weight loss and managing sugar levels.">
    <?php echo $__env->make('web.inc.maincss', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


    <style>
        .blog_text{
            position: inherit;
            width: 100%;
            left: 0;
        }
    </style>

</head>

<body>

 <!-- Start Include Header -->
    <?php echo $__env->make('web.inc.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- End Include Header -->




    <!--=========================
        BREADCRUMB START
    ==========================-->
    <section class="page_breadcrumb" style="background: url(images/breadcrumb_bg.jpg);">
        <div class="breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="breadcrumb_text wow fadeInUp">
                            <h1>Blogs</h1>
                            <ul>
                                <li><a href="index.php"><i class="fal fa-home-lg"></i> Home</a></li>
                                <li><a href="#">Blogs</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=========================
        BREADCRUMB START
    ==========================-->


    <!--=========================
        BLOG PAGE START
    ==========================-->
    <section class="blog_page ">
        <div class="container">
            <div class="row">
                
                    <?php ($table_name = 'blog'); ?>
                    <?php ($limit = 12); ?>
                    <?php ($list = DB::table('blog')                    
                    ->where($table_name.'.status',1)->orderBy($table_name.'.id','desc')); ?>
                    <?php ($list = $list->paginate($limit)); ?>
                    <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-4 col-md-6 wow fadeInUp">
                            <div class="blog_item">
                                <div class="blog_img">
                                    <img src="<?php echo e(Helpers::image_check(@$value->image)); ?>" alt="blog" class="img-fluid w-100">
                                </div>
                                <div class="blog_text">
                                    <ul class="top">
                                        <li><i class="far fa-calendar-day"></i> <?php echo e(date("d M, Y", strtotime($value->add_date_time))); ?></li>
                                        <li><i class="far fa-user-circle"></i> Admin</li>
                                    </ul>
                                    <a class="title" href="<?php echo e($value->slug); ?>"><?php echo e($value->name); ?></a>
                                    <ul class="bottom">
                                        <li><a href="<?php echo e($value->slug); ?>">read more <i class="fas fa-long-arrow-right"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                   
                    <div class="pagination page_navigation">
                      <?php echo e($list->links()); ?>                  
                    </div>
                <!-- <div class="pagination mt_60 wow fadeInUp">
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Previous">
                                <i class="far fa-angle-double-left"></i>
                            </a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link active" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                                <i class="far fa-angle-double-right"></i>
                            </a>
                        </li>
                    </ul>
                </div> -->
            </div>
        </div>
    </section>
    <!--=========================
        BLOG PAGE END
    ==========================-->


  
    <!-- Start Include Footer -->
    <?php echo $__env->make('web.inc.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- Enc Include Footer -->
    <!-- Start Include Footer -->
    <?php echo $__env->make('web.inc.mainjs', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- Enc Include Footer -->
</body>
</html><?php /**PATH /home/shivved1/public_html/resources/views/web/blogs.blade.php ENDPATH**/ ?>