
/* Example

const headerImageSliderSetting = new SliderProjectorSetting({
    sliderProjectorId: 'header-image-slider',
    slideQuery: '#header-image-slider .placeholder figure',
    dots: '#header-image-slider .slider-navigation-labels .navigation span',
    autoSlider: true,
    sliderInterval: 4000
});
headerImageSliderSetting = headerImageSliderSetting.prepareParameters();

const headerImageSlider = new SliderProjector(headerImageSliderSetting).init();

 */

let Sliders = [];

const sliderContainers = document.querySelectorAll('.js-slider');
const sliderContainersLength = sliderContainers.length;

for(let i =0; i < sliderContainersLength; i++){

    const slider = sliderContainers[i];
    const sliderId = slider.getAttribute('id');

    if(sliderId !== null){

        const Slidersetting = new SliderSetting({
            sliderId: sliderId,
            autoSlider: slider.getAttribute('data-auto-slide'),
            slideQuery: '#' + sliderId +' .js-slider-slide',
            navigationButtons: '#' + sliderId +' .js-slider-button',
            dots: '#' + sliderId +' .js-slider-indicator',
        });

        Sliders.push( new Slider(Slidersetting.prepareParameters()).init() );
    }
    else console.log('An image slider has no id...');
}


function SliderSetting(settingsObject) {

    const self = this;

    this.sliderId = '';
    this.autoSlider = false;
    this.sliderInterval = 4000;
    this.navigationButtons = '';
    this.dots = '';
    this.slideQuery = '';

    this.setSliderId = function (string) {
        this.sliderId = string;
        return this;
    };
    this.setAutoSlider = function (boolean) {
        this.autoSlider = boolean;
        return this;
    };
    this.setSliderInterval = function (integer) {
        this.sliderInterval = integer;
        return this;
    };
    this.setSlideQuery = function (string) {
        this.slideQuery = string;
        return this;
    };
    this.setNavigationButtons = function (string) {
        this.navigationButtons = string;
        return this;
    };
    this.setDots = function (string) {
        this.dots = string;
        return this;
    };
    this.getSliderId = function () {
        return this.sliderId;
    };
    this.getAutoSlider = function () {
        let autoSlide = this.autoSlider;
        if(!Number.isInteger(autoSlide)){
            if(autoSlide == false) autoSlide = false;
            else autoSlide = true;
        }
        return autoSlide;
    };
    this.getSliderInterval = function () {
        return this.sliderInterval;
    };
    this.getSlideQuery = function () {
        return this.slideQuery;
    };
    this.getNavigationButtons = function () {
        return this.navigationButtons;
    };
    this.getDots = function () {
        return this.dots;
    };

    // Invert setters to getters
    this.prepareParameters = function () {

        return {
            sliderId: self.getSliderId(),
            autoSlider: self.getAutoSlider(),
            sliderInterval: self.getSliderInterval(),
            navigationButtons: self.getNavigationButtons(),
            dots: self.getDots(),
            slideQuery: self.getSlideQuery()
        }

    };

    // Mass assign settings
    this.fill = function () {
        // Object.keys(settingsObject).forEach(function (key) {
        //     self[key] = settingsObject[key];
        // });

        const settingsObjectKeys = Object.keys(settingsObject);
        const settingsObjectLength = settingsObjectKeys.length;

        for(let i = 0; i < settingsObjectLength; i++){
            const key = settingsObjectKeys[i];
            self[key] = settingsObject[key];
        }

    };

    this.fill();

    return {
        sliderId: self.setSliderId,
        autoSlider: self.setAutoSlider,
        sliderInterval: self.setSliderInterval,
        navigationButtons: self.setNavigationButtons,
        dots: self.setDots,
        slideQuery: self.setSlideQuery,
        prepareParameters: self.prepareParameters
    };
}

function Slider(settings) {

    //Define Slider object
    const self = this;
    this.sliderObject = '';

    //SlideParameters
    this.activeSlideId = 0;
    this.previousSlideId = 0;
    this.nextSlideId = 0;
    this.availableSlides = 1;
    this.slides = [];
    this.autoSliderInterval = null;

    this.settings = {};

    this.init = function () {

        //Append settings to self
        this.settings = settings;

        //Assign needed elements and calculations
        this.sliderObject = document.getElementById(this.settings.sliderId);
        this.slides = document.querySelectorAll(this.settings.slideQuery);
        this.availableSlides = this.slides.length;
        this.activeSlideId = 0;

        // Set active slide (and possible previous and next classes)
        this.setSlide();

        // Swipe interaction
        const swipeGestures = new Hammer(this.sliderObject);
        swipeGestures.on('swipeleft', function () {
            self.resetAutoSlider();
            self.nextSlide();
            self.setSlide();
        });
        swipeGestures.on('swiperight', function () {
            self.resetAutoSlider();
            self.previousSlide();
            self.setSlide();
        });


        if (this.settings.navigationButtons !== '') {

            // Click interaction
            const navigationButtons = document.querySelectorAll(this.settings.navigationButtons);
            const navigationButtonsLength = navigationButtons.length;
            for(let i = 0; i < navigationButtonsLength; i++){
                const navigationButton = navigationButtons[i];
                navigationButton.addEventListener('click', function () {
                    self.clickNavigationButton(this);
                });
            }
        }

        if (this.settings.dots !== '') {
            // Click interaction
            const dots = document.querySelectorAll(this.settings.dots);
            const dotsLength = dots.length;
            // console.log(this.settings.dots);
            // console.log(dots);
            for(let i = 0; i < dotsLength; i++){
                const dot = dots[i];
                dot.addEventListener('click', function () {
                    self.clickDot(this);
                });
            }

        }

        self.autoSlider();
    };


    this.autoSlider = function (){

        // Reset the interval if defined
        if(this.autoSliderInterval !== null) clearInterval(this.autoSliderInterval);

        // Check if should auto slide before creating the interval
        // We do this inhere, because the autoslide function is also called by other functionality
        if(this.settings.autoSlider !== false && Number.isInteger(this.settings.sliderInterval)  ){

            this.autoSliderInterval = setInterval(function() {
                    self.nextSlide();
                    self.setSlide();
                }, this.settings.sliderInterval
            );
        }
    };

    this.resetAutoSlider = self.autoSlider;

    this.nextSlide = function () {
        this.activeSlideId++;
        if (this.activeSlideId >= this.availableSlides) this.activeSlideId = 0;
    };

    this.previousSlide = function () {
        this.activeSlideId--;
        if (this.activeSlideId < 0) this.activeSlideId = this.availableSlides - 1;
    };


    this.setSlide = function () {

        // Loop through the form elements
        const slidesLength = self.slides.length;
        for(let i = 0; i < slidesLength; i++){
            const slide = self.slides[i];

            slide.style.pointerEvents = "none"; // Needed for swipe functionality

            // Convert data set attribute to desired type
            const slideOrder = parseInt(slide.getAttribute('data-order'));

            // Remove and set active for all slides
            if (slideOrder !== self.activeSlideId) slide.classList.remove('is-active');
            else slide.classList.add('is-active');
        }

        if (self.settings.dots !== '') { self.setActiveDot(); }
    };

    this.clickNavigationButton = function (navButton) {

        const navButtonDirection = navButton.getAttribute('aria-label');

        if(navButtonDirection === 'next') this.nextSlide();
        else if(navButtonDirection === 'previous') this.previousSlide();

        self.setSlide();
        self.resetAutoSlider();
    };

    this.clickDot = function (clickedDot) {
        self.activeSlideId = parseInt(clickedDot.getAttribute('data-order'));
        self.setSlide();
        self.resetAutoSlider();
    };

    this.setActiveDot = function () {

        const dots = document.querySelectorAll(this.settings.dots);
        const dotsLength = dots.length;
        for(let i = 0; i < dotsLength; i++){
            const dot = dots[i];
            dotOrder = parseInt(dot.getAttribute('data-order'));

            if(dotOrder !== self.activeSlideId) {
                dot.classList.remove('is-active');
                dot.tabIndex = 0;
            }
            else {
                dot.classList.add('is-active');
                dot.tabIndex = -1;
            }
        }
    };
}