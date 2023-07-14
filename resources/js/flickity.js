import Flickity from 'flickity';
window.flickity = Flickity;


export function carousel() {
    return {
      active: 0,
      init() {
        var flkty = new Flickity(this.$refs.carousel, {
          pageDots: false,
          lazyLoad: 2,
          contain: false,
          draggable: '>1',
          wrapAround: true
        });
        flkty.on('change', i => this.active = i);
      }
    }
  }
window.carousel = carousel; 
export function carouselFilter() {
    return {
      active: 0,
      changeActive(i) {
        this.active = i;
        
        this.$nextTick(() => {
          let flkty = Flickity.data( this.$el.querySelectorAll('.carousel')[i] );
          flkty.resize();
        });
      }
    }
  }
window.carouselFilter = carouselFilter; 