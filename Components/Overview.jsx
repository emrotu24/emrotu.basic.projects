import MyDevKnowledgeBg from '../medias/MyDevKnowledgeBg.mp4'
import LastProjectsBg from '../medias/LastProjectsBg.mp4'
import WorkingProgressBg from '../medias/WorkingProgressBg.mp4'
import KnowmeBetterBg from '../medias/KnowmeBetterBg.mp4'

export default function Overview() {
    return(
        <section className="overview-section-box grid rid-cols-1 sm:grid-cols-2 gap-5 justify-items-center mx-5">

            <div className="my-knowledge-box relative group rounded-lg shadow-lg overflow-hidden"
                onMouseEnter={(e) => {
                    const video = e.currentTarget.querySelector('video');
                    video?.play();
                }}
                onMouseLeave={(e) => {
                    const video = e.currentTarget.querySelector('video');
                    video?.pause();
                }}>
                <div className="absolute top-0 left-0 w-full h-full bg-black/10 group-hover:backdrop-blur-[2px] transition duration-500 z-10" />
                <video src={MyDevKnowledgeBg}
                    className="absolute top-0 left-0 w-full h-full object-cover"
                    muted
                    loop
                    playsInline>
                </video>
                <div className='flex flex-col items-center justify-center text-center py-10 gap-5'>
                    <h2 className="z-10 group-hover:animate-bounce font-extrabold text-outline-white text-purple-800 text-4xl">MIS CONOCIMIENTOS</h2>
                    <p className="z-10 text-2xl text-outline-black text-white font-extrabold mx-20 leading-relaxed">Pulsa para una explicacion completa de como aprendi los conceptos basico de cada lenguaje</p>
                </div>
            </div>

            <div className="last-proyects-box relative group overflow-hidden rounded-lg shadow-lg"
                onMouseEnter={(e) => {
                    const video = e.currentTarget.querySelector('video');
                    video?.play();
                }}
                onMouseLeave={(e) => {
                    const video = e.currentTarget.querySelector('video');
                    video?.pause();
                }}>
                <div className="absolute top-0 left-0 w-full h-full bg-black/10 group-hover:backdrop-blur-[2px] transition duration-500 z-10" />
                <video src={LastProjectsBg}
                    className="absolute top-0 left-0 w-full h-full object-cover z-0"
                    muted
                    loop
                    playsInline>
                </video>
                <div className='flex flex-col items-center justify-center text-center py-10 gap-5'>
                    <h2 className="z-10 group-hover:animate-bounce font-extrabold text-outline-white text-purple-800 text-4xl">PROYECTOS RECIENTES</h2>
                    <p className="z-10 text-2xl text-outline-black text-white font-extrabold mx-20 leading-relaxed">Pulsa para ver mi lista de proyectos completado</p>  
                </div>
            </div>

            <div className="working-progress-box relative group overflow-hidden rounded-lg shadow-lg"
                onMouseEnter={(e) => {
                    const video = e.currentTarget.querySelector('video');
                    video?.play();
                }}
                onMouseLeave={(e) => {
                    const video = e.currentTarget.querySelector('video');
                    video?.pause();
                }}>
                <div className="absolute top-0 left-0 w-full h-full bg-black/10 group-hover:backdrop-blur-[2px] transition duration-500 z-10" />
                <video src={WorkingProgressBg}
                    className="absolute top-0 left-0 w-full h-full object-cover z-0"
                    muted
                    loop
                    playsInline>
                </video>
                <div className='flex flex-col items-center justify-center text-center py-10 gap-5'>
                    <h2 className="z-10 group-hover:animate-bounce font-extrabold text-outline-white text-purple-800 text-4xl">TRABAJANDOLO</h2>
                    <p className="z-10 text-2xl text-outline-black text-white font-extrabold mx-20 leading-relaxed">Pulsa para ver en que estoy trabajando es este momento</p>
                </div>
            </div>
            
            <div className="know-me-better-box relative group overflow-hidden rounded-lg shadow-lg"
                onMouseEnter={(e) => {
                    const video = e.currentTarget.querySelector('video');
                    video?.play();
                }}
                onMouseLeave={(e) => {
                    const video = e.currentTarget.querySelector('video');
                    video?.pause();
                }}>
                <div className="absolute top-0 left-0 w-full h-full bg-black/10 group-hover:backdrop-blur-[2px] transition duration-500 z-10" />
                <video src={KnowmeBetterBg}
                    className="absolute top-0 left-0 w-full h-full object-cover z-0"
                    muted
                    loop
                    playsInline>
                </video>
                <div className='flex flex-col items-center justify-center text-center py-10 gap-5'>
                    <h2 className="z-10 group-hover:animate-bounce font-extrabold text-outline-white text-purple-800 text-4xl">CONOCEME MEJOR</h2>
                    <p className="z-10 text-2xl text-outline-black text-white font-extrabold mx-20 leading-relaxed">Pulsa para saber más sobre mi, mi raiz, mi manera de ver las cosas</p>
                </div>
            </div>
        </section>
    )
}