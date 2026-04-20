import React, { useState } from 'react';

export const ProductVideo = ({ url, title = 'YouTube Video' }) => {
    const [isInteracting, setIsInteracting] = useState(false);
    
    return (
        <div className="aspect-video w-full max-h-[90dvh] mx-auto">
            <iframe
                className="w-full h-full"
                src={url}
                title={title}
                frameBorder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowFullScreen
            />
            
            {!isInteracting && (
                <div 
                    className="absolute inset-0 bg-transparent cursor-pointer z-10"
                    onMouseDown={() => setIsInteracting(true)}
                    onTouchStart={() => setIsInteracting(true)}
                />
            )}
            
            <span className="hidden">{isInteracting && setTimeout(() => setIsInteracting(false), 5000)}</span>
        </div>
    );
};