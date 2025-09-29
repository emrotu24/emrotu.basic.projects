import IntroductionWallpaper from '../images/IntroductionWallpaper.jpg';

export default function Introduction() {
  return(
    <section className="group introduction bg-cover bg-no-repeat bg-center flex flex-col text-center py-20 my-5 mx-5 rounded-lg shadow-lg"
            style={{ backgroundImage: `url(${IntroductionWallpaper})` }}>
        <h1 className="text-4xl text-outline-title font-bold text-purple-800 group-hover:animate-bounce transition pb-10">COSTRUYENDO MI CAMINO</h1>
        <p className="text-2xl text-outline text-white font-bold mx-40 leading-relaxed">Apasionado por la programación, con experiencia junior y una firme determinación de construir una carrera sólida en el mundo del desarrollo. Cada línea de código que escribo es un paso más hacia mi objetivo: crecer, aprender y aportar valor real a proyectos tecnológicos que marquen la diferencia.</p>
    </section>
  )
}