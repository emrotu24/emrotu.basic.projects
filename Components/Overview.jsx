export default function Overview() {
    return(
        <section className="grid rid-cols-1 sm:grid-cols-2 gap-4 justify-items-center mx-5">
            <div className="aspect-square w-full text-center rounded-lg shadow-lg bg-gray-500">
                <h2 className="font-semibold text-4xl">Dev knowledge</h2>
                <p>Pulsa para una explicacion completa de como aprendi los conceptos basico de cada lenguaje</p>
            </div>
            <div className="aspect-square w-full text-center rounded-lg shadow-lg bg-gray-500">
                <h2 className="font-semibold text-4xl">Last Project</h2>
                <p>Pulsa para ver mi lista de proyectos completado</p>
            </div>
            <div className="col-span-2 w-full text-center rounded-lg shadow-lg bg-gray-500">
                <h2 className="font-semibold text-4xl">Working progress</h2>
                <p>Pulsa para ver en que estoy trabajando es este momento</p>
            </div>
        </section>
    )
}