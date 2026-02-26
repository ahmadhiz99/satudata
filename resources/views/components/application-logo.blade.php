<svg viewBox="0 0 420 220" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>

    <defs>
        <linearGradient id="bgGrad" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#0f172a"/>
            <stop offset="100%" stop-color="#1e293b"/>
        </linearGradient>

        <linearGradient id="accentGrad" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#d4af37"/>
            <stop offset="100%" stop-color="#facc15"/>
        </linearGradient>
    </defs>

    <!-- Background -->
    <path d="M30 20 
             H390 
             Q410 20 410 40 
             V180 
             Q410 200 390 200 
             H30 
             Q10 200 10 180 
             V40 
             Q10 20 30 20 Z"
          fill="url(#bgGrad)"/>

    <!-- Clear Geometric S -->
    <path d="
        M160 60
        C130 40 90 50 90 85
        C90 110 110 120 135 125
        C165 130 175 140 165 155
        C150 175 105 170 85 150
        L85 170
        C115 190 175 185 190 150
        C205 115 170 105 140 100
        C110 95 105 80 120 70
        C135 60 155 65 170 75
        Z"
        fill="url(#accentGrad)"/>

    <!-- Letter D -->
    <path d="
        M230 50
        L260 50
        C330 50 360 75 360 110
        C360 145 330 170 260 170
        L230 170 Z

        M260 75
        L260 145
        C305 145 320 130 320 110
        C320 90 305 75 260 75 Z"
        fill="white"/>

</svg>