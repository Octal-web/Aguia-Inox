import { useEffect } from "react"; 
 
import { Swiper, SwiperSlide } from "swiper/react"; 
import "swiper/css"; 
 
import PhotoSwipeLightbox from "photoswipe/lightbox"; 
import "photoswipe/style.css"; 
 
export function Gallery({ items }) { 
    useEffect(() => {
        const lightbox = new PhotoSwipeLightbox({ 
            gallery: "#gallery-swiper", 
            children: "a", 
            pswpModule: () => import("photoswipe"),
            showHideAnimationType: 'fade',
            bgOpacity: 0.9,
        }); 

        lightbox.addFilter('uiElement', (element, data) => {
            if (data.name === 'caption') {
                return false;
            }
            return element;
        });

        lightbox.on('uiRegister', function() {
            lightbox.pswp.ui.registerElement({
                name: 'custom-caption',
                order: 9,
                isButton: false,
                appendTo: 'root',
                html: '',
                onInit: (el, pswp) => {
                    pswp.on('change', () => {
                        const currSlide = pswp.currSlide;
                        if (currSlide && currSlide.data && currSlide.data.element) {
                            const titulo = currSlide.data.element.getAttribute('data-pswp-titulo') || '';
                            const texto = currSlide.data.element.getAttribute('data-pswp-texto') || '';
                            
                            el.innerHTML = titulo || texto ? `
                                <div class="pswp__custom-caption">
                                    <div class="pswp__custom-caption-content">
                                        ${titulo ? `<h3 class="pswp__custom-caption-title">${titulo}</h3>` : ''}
                                        ${texto ? `<p class="pswp__custom-caption-text">${texto}</p>` : ''}
                                    </div>
                                </div>
                            ` : '';
                        }
                    });
                }
            });
        });
        
        lightbox.init(); 

        // Adiciona CSS customizado para o caption
        const style = document.createElement('style');
        style.textContent = `
            .pswp__custom-caption {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                background: linear-gradient(transparent, rgba(0,0,0,0.8));
                color: white;
                padding: 60px 40px 40px;
                z-index: 10;
                pointer-events: none;
            }
            
            .pswp__custom-caption-content {
                max-width: 600px;
            }
            
            .pswp__custom-caption-title {
                font-size: 28px;
                font-weight: 600;
                margin-bottom: 16px;
                line-height: 1.2;
                letter-spacing: -0.02em;
                margin-top: 0;
            }
            
            .pswp__custom-caption-text {
                font-size: 16px;
                line-height: 1.5;
                opacity: 0.9;
                margin: 0;
            }
            
            @media (max-width: 768px) {
                .pswp__custom-caption {
                    padding: 40px 20px 30px;
                }
                
                .pswp__custom-caption-title {
                    font-size: 24px;
                    margin-bottom: 12px;
                }
                
                .pswp__custom-caption-text {
                    font-size: 14px;
                }
            }
        `;
        document.head.appendChild(style);
 
        return () => { 
            lightbox.destroy();
            if (style.parentNode) {
                style.parentNode.removeChild(style);
            }
        }; 
    }, []); 
 
    return ( 
        <div id="gallery-swiper" className="mt-20 w-full"> 
            <Swiper 
                spaceBetween={20} 
                breakpoints={{ 
                    0: { slidesPerView: 1.8 }, 
                    601: { slidesPerView: 2.5 }, 
                    1025: { slidesPerView: 2.8 }, 
                    1365: { slidesPerView: 3.2 }, 
                }} 
            > 
                {items.map((item, index) => ( 
                    <SwiperSlide key={index}> 
                        <div> 
                            <a 
                                href={item.imagem} 
                                data-pswp-width="900" 
                                data-pswp-height="1200"
                                data-pswp-titulo={item.titulo}
                                data-pswp-texto={item.texto}
                                target="_blank" 
                                rel="noreferrer" 
                                className="group" 
                            > 
                                <div className="relative h-[600px] w-full overflow-hidden rounded-[10px] bg-primary/20 max-[601px]:h-[400px]"> 
                                    <img 
                                        src={item.imagem} 
                                        alt={item.titulo || ""} 
                                        className="absolute h-full w-full object-cover mix-blend-soft-light" 
                                    /> 
                                    <div className="absolute inset-0 bg-primary bg-linear-to-b from-transparent to-black/50 mix-blend-soft-light opacity-75 transition-all group-hover:opacity-40" /> 
                                </div> 
                            </a> 
                            <p className="mt-12 font-sora text-2xl font-medium tracking-tight text-textblack"> 
                                {item.titulo} 
                            </p> 
                            <p className="mt-5 max-w-[393px] tracking-tight text-textblack"> 
                                {item.texto} 
                            </p> 
                        </div> 
                    </SwiperSlide> 
                ))} 
            </Swiper> 
        </div> 
    ); 
}