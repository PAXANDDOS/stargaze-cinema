export const Logo = (props: React.SVGProps<SVGSVGElement>) => (
    <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg" {...props}>
        <circle cx={100} cy={100} r={95} fill="#5F7ECE" stroke="#1B3371" strokeWidth={10} />
        <path
            stroke="#1B3371"
            strokeWidth={10}
            strokeLinecap="round"
            d="M6 98.929 98.929 6M10 130.208 130.208 10"
        />
        <circle cx={125} cy={131} r={24} fill="#fff" stroke="#4560A8" strokeWidth={2} />
    </svg>
)
