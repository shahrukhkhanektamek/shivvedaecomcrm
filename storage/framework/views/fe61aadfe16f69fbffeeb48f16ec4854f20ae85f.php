<?php echo $__env->make('web.inc.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



			<!-- Start Hero Area -->
			<div class="hero-area style-1" style="background-image:url(<?php echo e(url('public/web/')); ?>/img/Clip.png)">
				<div class="hero-content-wrap">
					<div class="container custom-container">
						<div class="row">
							<div class="col-md-6">
								<!-- Start hero content -->
								<div class="hero-content">
									<h1>Never Stop Learning Life Never Stop Teaching</h1>
									<p>Every teaching and learning journey is unique Following We'll help guide your way.</p>
									<a href="<?php echo e(url('public/web/')); ?>/cart.html" class="buttonfx angleindouble color-1 mb-2"><i class="icofont-shopping-cart"></i> ENROLL NOW</a>
								</div>
								<!-- End hero content -->
							</div>
							<div class="col-md-6">
								<!-- Start hero right -->
								<div class="hero-right">
									<!-- start hero discount -->
									<div class="hero-discount">
										<h1>25 <sup>%</sup><span>OFF</span></h1>
										<p>ONLY THIS WEEK</p>
									</div>
									<!-- end hero discount -->
								</div>
								<!-- End hero right -->
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Hero Area -->
			<!-- Start Feature Area -->
			<div class="feature-area pt-110pb-68" id="features">
				<div class="container custom-container">
					<div class="row">
						<div class="col-md-6">
							<!-- Start section title -->
							<div class="section-title width-80" data-aos="fade-up">
								<h2>25 Of Top Courses Now in One Place</h2>
								<p>Discover a curated selection of the 25 best courses offered by KnowledgeWaveIndia, covering a diverse range of subjects. Whether you're looking to enhance your skills in technology, business, arts, or personal development, these top-rated courses provide comprehensive content and expert instruction to help you achieve your learning goals.</p>
							</div>
							<!-- End section title -->
							<div class="row">
								<div class="col-md-6">
									<!-- Start single feature -->
									<div class="single-feature animation-1" data-aos="fade-right">
										<img src="<?php echo e(url('public/web/')); ?>/img/feature/feature-1.png" alt="Feature">
										<h2>The Most World Class Instructors</h2>
									</div>
									<!-- End single feature -->
								</div>
								<div class="col-md-6">
									<!-- Start single feature -->
									<div class="single-feature" data-aos="fade-left">
										<img src="<?php echo e(url('public/web/')); ?>/img/feature/feature-2.png" alt="Feature">
										<h2>Access Your Class anywhere</h2>
									</div>
									<!-- End single feature -->
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<!-- Start single feature -->
									<div class="single-feature" data-aos="fade-up">
										<img src="<?php echo e(url('public/web/')); ?>/img/feature/feature-3.png" alt="Feature">
										<h2>Flexible Course Plan</h2>
									</div>
									<!-- End single feature -->
								</div>
								<div class="col-md-6">
									<!-- Start single feature -->
									<div class="single-feature" data-aos="fade-down">
										<img src="<?php echo e(url('public/web/')); ?>/img/feature/feature-4-1.png" alt="Feature">
										<h2>Student Friendly</h2>
									</div>
									<!-- End single feature -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Feature Area -->




			<!-- Start Pricing Plan -->
			<div class="pricint-plan-area pt-110pb-90">
				<div class="container custom-container">
					<div class="row justify-content-center">
						<div class="col-md-6">
							<!-- Start section title -->
							<div class="section-title text-center" data-aos="fade-up">
								<h2>Explore Our World's Best Packages</h2>
								<p>Explore the world's best learning packages with KnowledgeWaveIndia, offering top-notch courses in various fields.</p>
							</div>
							<!-- End section title -->
						</div>
					</div>
					<div class="row speaker-model-slide">
						
<?php ($all_packages = \App\Models\Package::all_packages()); ?>
<?php $__currentLoopData = $all_packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<div class="col-md-12">
							<div class="single-pricing text-center" data-aos="fade-right">
								<h3 class="pricing-title"><?php echo e($value->name); ?></h3>
								<img src="<?php echo e(asset('storage/app/public/upload/')); ?>/<?php echo e(@$value->offer_image); ?>" alt="">
								<div class="price-box">
									
										<h1 class="price"><?php echo e(Helpers::price_formate($value->sale_price)); ?></h1>
										<h4 class="price price-cut"><?php echo e(Helpers::price_formate($value->real_price)); ?></h4>
									
								</div>
								
								<a class="buttonfx angleindouble color-2" href="contact.html"><i class="icofont-shopping-cart"></i>Enroll Now</a>
							</div>
						</div>
 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

						
					</div>
				</div>
			</div>
			<!-- End Pricing Plan -->






			
			<!-- Start Technical Space Area -->
			<div class="techinical-area pt-110">
				<div class="container custom-container">
					<div class="row justify-content-center">
						<div class="col-md-7 col-lg-6">
							<!-- Start section title -->
							<div class="section-title white text-center" data-aos="fade-up">
								<h2>Start your Learning Journey Today!</h2>
								<p>Begin your educational adventure today with <?php echo e(env("APP_NAME")); ?>!</p>
							</div>
							<!-- End section title -->
						</div>
					</div>
					<div class="row">
						<div class="col-lg-3 col-sm-6">
							<!-- start single techinical -->
							<div class="single-techinical text-center" data-aos="fade-right">
								<img src="<?php echo e(url('public/web/')); ?>/img/techinical-1.png" alt="">
								<h4>Learn with Experts</h4>
								<p>Gain knowledge from industry experts with KnowledgeWaveIndia.</p>
							</div>
							<!-- end single techinical -->
						</div>
						<div class="col-lg-3 col-sm-6">
							<!-- start single techinical -->
							<div class="single-techinical text-center" data-aos="fade-up">
								<img src="<?php echo e(url('public/web/')); ?>/img/techinical-2.png" alt="">
								<h4>Learn Anything</h4>
								<p>Master any subject with KnowledgeWaveIndia's diverse course offerings.</p>
							</div>
							<!-- end single techinical -->
						</div>
						<div class="col-lg-3 col-sm-6">
							<!-- start single techinical -->
							<div class="single-techinical text-center" data-aos="fade-down">
								<img src="<?php echo e(url('public/web/')); ?>/img/techinical-3.png" alt="">
								<h4>Get Online Certificate</h4>
								<p>Earn an online certificate with KnowledgeWaveIndia's accredited courses.</p>
							</div>
							<!-- end single techinical -->
						</div>
						<div class="col-lg-3 col-sm-6">
							<!-- start single techinical -->
							<div class="single-techinical text-center" data-aos="fade-up">
								<img src="<?php echo e(url('public/web/')); ?>/img/techinical-4.png" alt="">
								<h4>Comprehensive Learning</h4>
								<p>Experience comprehensive learning with KnowledgeWaveIndia's extensive course catalog.</p>
							</div>
							<!-- end single techinical -->
						</div>
					</div>
					
				</div>
			</div>
			<!-- End Technical Space Area -->
			
			<!-- Start product Area -->
			<div id="product" class="product-area pb-80 mt-115">
				<div class="container custom-container">
					<div class="row justify-content-center">
						<div class="col-md-7">
							<!-- Start section title -->
							<div class="section-title text-center" data-aos="fade-up">
								<h2>Our Top Class & Expert Instructors in One Place</h2>
								<p>Learn from top-class, expert instructors all in one place with <?php echo e(env("APP_NAME")); ?></p>
							</div>
							<!-- End section title -->
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="watch-model-slide">
								
								<?php ($get_all_teacher_home = \App\Models\Teacher::get_all_teacher_home()); ?>
								<?php $__currentLoopData = $get_all_teacher_home; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

									<!-- Start single woatch model slide -->
									<div class="watch-model-wrap" data-aos="fade-right">
										<!-- Start single watch model -->
										<div class="single-watch-model">
											<!-- start model  -->
											<div class="model-wrap">
												<div class="model-img text-center">
													<img src="<?php echo e(asset('storage/app/public/upload/')); ?>/<?php echo e(@$value->image); ?>" alt="">
												</div>
											</div>
											<!-- end model  -->
											<div class="model-content">
												<a ><h3><?php echo e($value->name); ?></h3></a>
												<a ><h6><?php echo e($value->profession); ?></h6></a>
											</div>
										</div>
										<!-- End single watch model -->
									</div>
									<!-- End single woatch model slide -->
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Product Area -->



			<div class="funfacts-area pb-87 pt-65" style="background-image: url(<?php echo e(url('public/web/')); ?>/img/funfacts-bg.svg);">
				<div class="container">
					
					<div class="row">
						<div class="col-md-4">
							<!-- start single fact -->
							<div class="single-fact text-center" data-aos="fade-right">
								<span class="counter">22</span>
								<span class="counter-extra">k+</span>
								<h4>Active Students</h4>
								<p>Led all cottage met enabled attempt through talking delight.</p>
							</div>
							<!-- end single fact -->
						</div>
						<div class="col-md-4">
							<!-- start single fact -->
							<div class="single-fact text-center" data-aos="fade-up">
								<span class="counter">25</span>
								<span class="counter-extra">k</span>
								<h4>Courses</h4>
								<p>Led all cottage met enabled attempt through talking delight.</p>
							</div>
							<!-- end single fact -->
						</div>
						<div class="col-md-4">
							<!-- start single fact -->
							<div class="single-fact text-center" data-aos="fade-right">
								<span class="counter">15</span>
								<span class="counter-extra">k</span>
								<h4>Best Trainers</h4>
								<p>Led all cottage met enabled attempt through talking delight.</p>
							</div>
							<!-- end single fact -->
						</div>
					</div>
				</div>
			</div>

			<!-- Start News Leater Area -->
			<div class="newsleater-area mb-120 mt-115" data-aos="fade-up">
				<div class="container">
					<div class="row">
						<div class="col-md-6">
							<!-- Start section title -->
							<div class="section-title">
								<h2>Latest Update And New Offers Notification</h2>
								<p>Delay rapid joy share allow age manor six. Went why far saw many knew. Exquisite excellent son gentleman acuteness her.</p>
							</div>
							<!-- End section title -->
							<!-- Start form -->
							<form action="#" method="post" class="subscribe-form watch">
								<input name="email" class="widget-input" placeholder="Email address" type="email">
								<button type="submit" class="widget-sbtn">SUBSCRIBE</button>
							</form>
							<!-- End form -->
						</div>
						<div class="col-md-6"></div>
					</div>
				</div>
			</div>
			<!-- End News Leater Area -->



<?php echo $__env->make('web.inc.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\projects\ayurvedicmlm\resources\views/web/index.blade.php ENDPATH**/ ?>