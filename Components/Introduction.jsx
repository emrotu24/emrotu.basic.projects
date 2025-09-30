import IntroductionWallpaper from '../medias/IntroductionWallpaper.jpg';

export default function Introduction() {
  return(
    <section className="relative overflow-hidden group introduction bg-cover bg-no-repeat bg-center py-20 my-5 mx-5 rounded-lg shadow-lg"
            style={{ backgroundImage: `url(${IntroductionWallpaper})`}}>
        <div className="absolute top-0 left-0 w-full h-full bg-black/25 group-hover:backdrop-blur-[2px] transition duration-500"/>
        <div className='flex flex-col items-center justify-center text-center gap-5'>
          <h1 className="z-10 text-4xl text-outline-white font-extrabold text-purple-800 group-hover:animate-bounce">COSTRUYENDO MI CAMINO</h1>
          <p className="z-10 text-2xl text-outline-black text-white font-bold mx-40 leading-relaxed">Apasionado por la programación, con experiencia junior y una firme determinación de construir una carrera sólida en el mundo del desarrollo. Cada línea de código que escribo es un paso más hacia mi objetivo: crecer, aprender y aportar valor real a proyectos tecnológicos que marquen la diferencia.</p>
        </div>
    </section>
  )
}