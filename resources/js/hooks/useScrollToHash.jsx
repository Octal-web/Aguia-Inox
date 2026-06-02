import { useEffect } from "react";

export default function useScrollToHash() {
    useEffect(() => {
        const hash = window.location.hash;

        if (hash) {
            const el = document.querySelector(hash);

            if (el) {
                setTimeout(() => {
                    el.scrollIntoView({ behavior: "smooth", block: "start" });
                }, 100);
            }
        }
    }, []);
}
