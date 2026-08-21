{{--
    Brands Icons Slider Component

    Usage:
    - Include this template with: @include('frontend.includes.brands-icons')
    - To hide navigation arrows: @include('frontend.includes.brands-icons', ['showArrows' => false])
    - To show navigation arrows (default): @include('frontend.includes.brands-icons', ['showArrows' => true])
--}}

@php
    // Default to showing arrows if not specified
    $showArrows = $showArrows ?? true;
@endphp
<section class="section-padding brands">
    <div class="container pb-20">
        <span class="section-title mb-20 wow fadeIn animated"><span>Featured</span> Brands</span>
        <div class="custom-slider-container position-relative wow fadeIn animated mt-20" style="height: 110px">
            @if($showArrows)
            <div class="custom-slider-arrows" id="custom-slider-arrows">
                <button class="slider-arrow prev-arrow"><i class="fi-rs-angle-left"></i></button>
                <button class="slider-arrow next-arrow"><i class="fi-rs-angle-right"></i></button>
            </div>
            @endif
            <div class="custom-slider" id="brands-slider">
                <div class="custom-slider-track">
                    <div class="slides">
                        <img class="" width="216" height="84" loading="lazy" src="https://res.cloudinary.com/dhxnzgb0d/image/upload/w_216,f_auto,q_auto/v1749667216/12_gsyhhz.png" alt="Brand Logo 1" title="Brand Logo 1">
                    </div>
                    <div class="slides">
                        <img class="" width="216" height="84" loading="lazy" src="https://res.cloudinary.com/dhxnzgb0d/image/upload/w_216,f_auto,q_auto/v1749667211/6_m1wlai.png" alt="Brand Logo 3" title="Brand Logo 3">
                    </div>
                    <div class="slides">
                        <img class="" width="216" height="84" loading="lazy" src="https://res.cloudinary.com/dhxnzgb0d/image/upload/w_216,f_auto,q_auto/v1749667211/4_mu1rvz.png" alt="Brand Logo 6" title="Brand Logo 6">
                    </div>
                    <div class="slides">
                        <img class="" width="216" height="84" loading="lazy" src="https://res.cloudinary.com/dhxnzgb0d/image/upload/w_216,f_auto,q_auto/v1749667212/15_hiba3d.png" alt="Brand Logo 8" title="Brand Logo 8">
                    </div>
                    <div class="slides">
                        <img class="" width="216" height="84" loading="lazy" src="https://res.cloudinary.com/dhxnzgb0d/image/upload/w_216,f_auto,q_auto/v1749667212/9_irittc.png" alt="Brand Logo 9" title="Brand Logo 9">
                    </div>
                    <div class="slides">
                        <img class="" width="216" height="84" loading="lazy" src="https://res.cloudinary.com/dhxnzgb0d/image/upload/w_216,f_auto,q_auto/v1749667213/13_gag7mx.png" alt="Brand Logo 10" title="Brand Logo 10">
                    </div>
                    <div class="slides">
                        <img class="" width="216" height="84" loading="lazy" src="https://res.cloudinary.com/dhxnzgb0d/image/upload/w_216,f_auto,q_auto/v1749667217/17_xgdday.png" alt="Brand Logo 11" title="Brand Logo 11">
                    </div>
                    <div class="slides">
                        <img class="" width="216" height="84" loading="lazy" src="https://res.cloudinary.com/dhxnzgb0d/image/upload/w_216,f_auto,q_auto/v1749667213/10_de2nyi.png" alt="Brand Logo 12" title="Brand Logo 12">
                    </div>
                    <div class="slides">
                        <img class="" width="216" height="84" loading="lazy" src="https://res.cloudinary.com/dhxnzgb0d/image/upload/w_216,f_auto,q_auto/v1749667213/20_r3tuhl.png" alt="Brand Logo 13" title="Brand Logo 13">
                    </div>
                    <div class="slides">
                        <img class="" width="216" height="84" loading="lazy" src="https://res.cloudinary.com/dhxnzgb0d/image/upload/w_216,f_auto,q_auto/v1749667213/19_z0w9ok.png" alt="Brand Logo 14" title="Brand Logo 14">
                    </div>
                    <div class="slides">
                        <img class="" width="216" height="84" loading="lazy" src="https://res.cloudinary.com/dhxnzgb0d/image/upload/w_216,f_auto,q_auto/v1749667212/8_oxsy82.png" alt="Brand Logo 15" title="Brand Logo 15">
                    </div>
                    <div class="slides">
                        <img class="" width="216" height="84" loading="lazy" src="https://res.cloudinary.com/dhxnzgb0d/image/upload/w_216,f_auto,q_auto/v1749667216/14_bfhyb0.png" alt="Brand Logo 16" title="Brand Logo 16">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@push('styles')
    <style>.slides{margin:0 20px;flex:0 0 auto}.custom-slider-container{position:relative;overflow:hidden}.custom-slider{position:relative;width:100%;overflow:hidden}.custom-slider-track{display:flex;transition:transform 0.5s ease;cursor:grab}.custom-slider-track.dragging{cursor:grabbing;transition:none}.custom-slider-arrows{position:absolute;top:20px;right:10px;display:flex;justify-content:flex-end;z-index:10}.slider-arrow{background:#fff;border:1px solid #e2e9e1;border-radius:50%;box-shadow:0 2px 4px rgba(0,0,0,0.1);cursor:pointer;width:40px;height:40px;display:flex;align-items:center;justify-content:center;transition:all 0.3s ease}.slider-arrow:hover{background:#3BB77E;color:#fff}.prev-arrow{margin-right:50px}.next-arrow{margin-right:0}@media (max-width:767px){section.brands{display:none}}</style>
@endpush
@push('scripts')
    <script>document.addEventListener('livewire:load',function(){const slider=document.getElementById('brands-slider');const sliderTrack=slider.querySelector('.custom-slider-track');const slides=sliderTrack.querySelectorAll('.slides');const prevBtn=document.querySelector('.prev-arrow');const nextBtn=document.querySelector('.next-arrow');const arrowsEnabled={{ $showArrows ? 'true' : 'false' }};const slidesToShow=getSlideToShow();let currentPosition=0;let slideWidth=0;let autoplayInterval;let isDragging=false;let startPosition=0;let currentTranslate=0;let prevTranslate=0;let animationID=0;function initSlider(){slideWidth=slider.offsetWidth/slidesToShow;slides.forEach(slide=>{slide.style.width=`${slideWidth}px`});const slidesToClone=2*slidesToShow;for(let i=0;i<slidesToClone;i++){const clone=slides[i%slides.length].cloneNode(true);sliderTrack.appendChild(clone)}updateSliderPosition();sliderTrack.addEventListener('mousedown',dragStart);sliderTrack.addEventListener('touchstart',dragStart);sliderTrack.addEventListener('mouseup',dragEnd);sliderTrack.addEventListener('touchend',dragEnd);sliderTrack.addEventListener('mouseleave',dragEnd);sliderTrack.addEventListener('mousemove',drag);sliderTrack.addEventListener('touchmove',drag);sliderTrack.addEventListener('contextmenu',e=>e.preventDefault());startAutoplay()}function getSlideToShow(){const width=window.innerWidth;if(width<480)return 1;if(width<768)return 3;if(width<1025)return 4;return 6}function updateSliderPosition(){sliderTrack.style.transform=`translateX(${-currentPosition*slideWidth}px)`;if(currentPosition>=slides.length){setTimeout(()=>{sliderTrack.style.transition='none';currentPosition=0;sliderTrack.style.transform=`translateX(${-currentPosition*slideWidth}px)`;sliderTrack.offsetHeight;sliderTrack.style.transition='transform 0.5s ease'},500)}else if(currentPosition<0){setTimeout(()=>{sliderTrack.style.transition='none';currentPosition=slides.length-1;sliderTrack.style.transform=`translateX(${-currentPosition*slideWidth}px)`;sliderTrack.offsetHeight;sliderTrack.style.transition='transform 0.5s ease'},500)}}function nextSlide(){currentPosition++;updateSliderPosition()}function prevSlide(){currentPosition--;updateSliderPosition()}function startAutoplay(){stopAutoplay();autoplayInterval=setInterval(nextSlide,3000)}function stopAutoplay(){if(autoplayInterval){clearInterval(autoplayInterval)}}if(arrowsEnabled&&prevBtn&&nextBtn){nextBtn.addEventListener('click',function(){stopAutoplay();nextSlide();startAutoplay()});prevBtn.addEventListener('click',function(){stopAutoplay();prevSlide();startAutoplay()})}slider.addEventListener('mouseenter',stopAutoplay);slider.addEventListener('mouseleave',startAutoplay);function dragStart(e){e.preventDefault();startPosition=getPositionX(e);isDragging=true;stopAutoplay();currentTranslate=-currentPosition*slideWidth;prevTranslate=currentTranslate;sliderTrack.classList.add('dragging');animationID=requestAnimationFrame(animation)}function drag(e){if(isDragging){const currentPosition=getPositionX(e);const diff=currentPosition-startPosition;currentTranslate=prevTranslate+diff}}function dragEnd(){if(!isDragging)return;cancelAnimationFrame(animationID);sliderTrack.classList.remove('dragging');const moveDistance=currentTranslate-prevTranslate;if(Math.abs(moveDistance)>slideWidth*0.2){if(moveDistance<0){currentPosition++}else{currentPosition--}}isDragging=false;sliderTrack.style.transition='transform 0.5s ease';updateSliderPosition();startAutoplay()}function animation(){if(isDragging){setSliderPosition();requestAnimationFrame(animation)}}function getPositionX(e){return e.type.includes('mouse')?e.pageX:e.touches[0].clientX}function setSliderPosition(){sliderTrack.style.transform=`translateX(${currentTranslate}px)`}window.addEventListener('resize',function(){const newSlidesToShow=getSlideToShow();if(newSlidesToShow!==slidesToShow){location.reload()}});initSlider()});</script>
@endpush

