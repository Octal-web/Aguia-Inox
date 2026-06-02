import { useRef, useState } from "react";

import { Reveal } from "./Reveal";

export function StampVideo({ src, poster }) {
    const videoRef = useRef(null);
    const [isPlaying, setIsPlaying] = useState(false);

    const togglePlay = () => {
        const video = videoRef.current;
        if (!video) return;

        if (video.paused) {
            video.play();
          setIsPlaying(true);
        } else {
            video.pause();
          setIsPlaying(false);
        }
    };

    return (
        <Reveal direction="bottom">
            <div
                className="relative w-full max-w-[1175px] cursor-pointer group"
                onClick={togglePlay}
            >
                <video
                    ref={videoRef}
                    src={src}
                    poster={poster}
                    className="h-[450px] w-full object-cover max-[1536px]:h-[300px] max-[1536px]:max-w-[870px] max-[1367px]:max-w-[740px] max-[1281px]:max-w-[670px] max-[1025px]:max-w-full"
                />

                <div className="absolute inset-0 bg-primary mix-blend-overlay" />

                {!isPlaying ? (
                    <div className="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="134"
                            height="103.195"
                            viewBox="0 0 134 103.195"
                            className="drop-shadow-lg transition-all group-hover:opacity-80 group-hover:scale-90"
                        >
                            <path
                                d="M185.523-671.759l38.506-24.836-11.966-7.658-26.54-16.985ZM142.4-644.8a10.045,10.045,0,0,1-7.391-3.006A10.046,10.046,0,0,1,132-655.2v-82.4a10.046,10.046,0,0,1,3.006-7.391A10.046,10.046,0,0,1,142.4-748H255.6a10.046,10.046,0,0,1,7.391,3.006A10.046,10.046,0,0,1,266-737.6v82.4a10.046,10.046,0,0,1-3.006,7.391A10.046,10.046,0,0,1,255.6-644.8Zm0-4.236H255.6a5.888,5.888,0,0,0,4.236-1.925,5.888,5.888,0,0,0,1.925-4.236v-82.4a5.888,5.888,0,0,0-1.925-4.236,5.888,5.888,0,0,0-4.236-1.925H142.4a5.888,5.888,0,0,0-4.236,1.925,5.888,5.888,0,0,0-1.925,4.236v82.4a5.888,5.888,0,0,0,1.925,4.236A5.888,5.888,0,0,0,142.4-649.04Zm-6.161,0v0Z"
                                transform="translate(-132 748)"
                                fill="#fff"
                            />
                        </svg>
                    </div>
                ) : (
                    <div className="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="134"
                            height="103.195"
                            viewBox="0 0 134 103.195"
                            className="drop-shadow-lg transition-all opacity-0 group-hover:opacity-60 group-hover:scale-90"
                        >
                        <path d="M142.4-644.8a10.045,10.045,0,0,1-7.391-3.006A10.046,10.046,0,0,1,132-655.2v-82.4a10.046,10.046,0,0,1,3.006-7.391A10.046,10.046,0,0,1,142.4-748H255.6a10.046,10.046,0,0,1,7.391,3.006A10.046,10.046,0,0,1,266-737.6v82.4a10.046,10.046,0,0,1-3.006,7.391A10.046,10.046,0,0,1,255.6-644.8Zm0-4.236H255.6a5.888,5.888,0,0,0,4.236-1.925,5.888,5.888,0,0,0,1.925-4.236v-82.4a5.888,5.888,0,0,0-1.925-4.236,5.888,5.888,0,0,0-4.236-1.925H142.4a5.888,5.888,0,0,0-4.236,1.925,5.888,5.888,0,0,0-1.925,4.236v82.4a5.888,5.888,0,0,0,1.925,4.236A5.888,5.888,0,0,0,142.4-649.04Zm-6.161,0v0Z" transform="translate(-132 748)" fill="#fff" />
                        
                        <rect x="50" y="28" width="10" height="47" rx="2" fill="#fff" />
                        
                        <rect x="74" y="28" width="10" height="47" rx="2" fill="#fff" />
                        </svg>
                    </div>
                )}
            </div>
        </Reveal>
    );
}
