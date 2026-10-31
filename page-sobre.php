<?php
/**
 * Sobre — texto institucional da Neblina. Fixo no código, mas bilíngue PT/ES.
 */
get_header();

// Texto institucional em PT e ES. Cliente não edita (combinado com Luu em 21/10).
$institutional_pt = [
    'Editorial Neblina: Uma Força Vivificante do Pensamento e da Literatura Brasileira na Espanha',
    'O nome Neblina, mantido em português, não é uma escolha meramente estética; ele nasce de uma raiz profunda e ancestral. O termo é a tradução de um conceito central da cosmologia guarani: tataxĩna. Na tradição originária, a tataxĩna é a neblina vivificante, uma espécie de orvalho primitivo e de fumaça sagrada por meio da qual se iniciou a comunicação do humano com as entidades superiores. Ela simboliza a renovação da vida. É uma umidade densa e silenciosa que atravessa fronteiras invisíveis, nutrindo o solo para que a vida e as boas palavras voltem a florescer.',
    'O Símbolo: Uma Ponte Gráfica, Sonora e Arquitetônica',
    'O til (~), sinal que preenche a palavra tataxĩna no original, é também parte central da logomarca da nossa editora. Essa escolha busca consolidar visualmente a identidade do projeto, materializando a imagem de um movimento ondular que, ao fim e ao cabo, funciona como um manifesto cultural.',
    'O Diagnóstico: O "Boom" que Esqueceu o Brasil',
    'Nos anos 1960 e 1970, a Espanha — com especial protagonismo de Barcelona — atuou como a base irradiadora do Boom da Literatura Latino-Americana. Contudo, devido a barreiras linguísticas e ao isolamento gerado pela própria dimensão continental do Brasil, os livros brasileiros não participaram tanto desse fenômeno, não na mesma escala.',
    'Hoje, nosso diagnóstico é de que essa assimetria persiste, mas o contexto aponta para uma imensa oportunidade de retificação:',
    'O Momento Atual: Um Protagonismo Brasileiro no Mundo',
    'O mundo contemporâneo, em algumas medidas, tem invertido as lógicas de consumo cultural, e o protagonismo do Brasil já é uma realidade comercial e crítica em diversas frentes criativas.',
    'Esse movimento é perceptível em vários aspectos. No cinema, em um recorte temporal mais recente, o caminho internacional foi aberto por marcos históricos como Cidade de Deus, e hoje se consolida com obras que lotam salas europeias e arrebatam prêmios: Ainda Estou Aqui (Oscar), O Agente Secreto (Cannes, Globo de Ouro) e O Último Azul (Urso de Prata em Berlim).',
    'Na beleza, moda e design, marcas com DNA brasileiro ditam tendências em Madri, Paris e Londres: Havaianas, Farm Rio, Granado, Melissa, Natura. A música brasileira lota arenas com Anitta, Liniker e Alok; a gastronomia é reconhecida mundialmente com Alex Atala, Filipe Bronze e Helena Rizzo.',
    'O Brasil transformou-se em artigo de desejo.',
    'Se o mercado europeu já veste o nosso design e se emociona com o nosso cinema, a demanda pela literatura pode e deve ser o próximo passo. O mundo já se apaixonou pela estética brasileira; assim, a Neblina surge para garantir que esse mesmo público aprecie também a nossa intelectualidade.',
];

$institutional_es = [
    'Editorial Neblina: Una Fuerza Vivificante del Pensamiento y de la Literatura Brasileña en España',
    'El nombre Neblina, mantenido en portugués, no es una elección meramente estética; nace de una raíz profunda y ancestral. El término es la traducción de un concepto central de la cosmología guaraní: tataxĩna. En la tradición originaria, la tataxĩna es la neblina vivificante, una especie de rocío primitivo y de humo sagrado a través del cual se inició la comunicación del humano con las entidades superiores. Simboliza la renovación de la vida. Es una humedad densa y silenciosa que atraviesa fronteras invisibles, nutriendo el suelo para que la vida y las buenas palabras vuelvan a florecer.',
    'El Símbolo: Un Puente Gráfico, Sonoro y Arquitectónico',
    'La tilde (~), signo que ocupa la palabra tataxĩna en el original, es también parte central del logotipo de nuestra editorial. Esta elección busca consolidar visualmente la identidad del proyecto, materializando la imagen de un movimiento ondular que, al fin y al cabo, funciona como un manifiesto cultural.',
    'El Diagnóstico: El "Boom" que Olvidó a Brasil',
    'En los años 1960 y 1970, España — con especial protagonismo de Barcelona — actuó como la base irradiadora del Boom de la Literatura Latinoamericana. Sin embargo, debido a barreras lingüísticas y al aislamiento generado por la propia dimensión continental de Brasil, los libros brasileños no participaron tanto en ese fenómeno, no en la misma escala.',
    'Hoy, nuestro diagnóstico es que esta asimetría persiste, pero el contexto apunta hacia una inmensa oportunidad de rectificación:',
    'El Momento Actual: Un Protagonismo Brasileño en el Mundo',
    'El mundo contemporáneo, en algunas medidas, ha invertido las lógicas de consumo cultural, y el protagonismo de Brasil ya es una realidad comercial y crítica en diversos frentes creativos.',
    'Este movimiento es perceptible en varios aspectos. En el cine, en un recorte temporal más reciente, el camino internacional fue abierto por hitos históricos como Ciudad de Dios, y hoy se consolida con obras que llenan salas europeas y arrebatan premios: Aún Estoy Aquí (Oscar), El Agente Secreto (Cannes, Globo de Oro) y El Último Azul (Oso de Plata en Berlín).',
    'En belleza, moda y diseño, marcas con ADN brasileño dictan tendencias en Madrid, París y Londres: Havaianas, Farm Rio, Granado, Melissa, Natura. La música brasileña llena arenas con Anitta, Liniker y Alok; la gastronomía es reconocida mundialmente con Alex Atala, Filipe Bronze y Helena Rizzo.',
    'Brasil se ha convertido en artículo de deseo.',
    'Si el mercado europeo ya viste nuestro diseño y se emociona con nuestro cine, la demanda por la literatura puede y debe ser el próximo paso. El mundo ya se enamoró de la estética brasileña; así, Neblina surge para garantizar que ese mismo público aprecie también nuestra intelectualidad.',
];

$institutional = neblina_lang() === 'pt' ? $institutional_pt : $institutional_es;
?>

<div class="shell institutional-page">
    <div class="page-intro">
        <div class="eyebrow">NEBLINA EDITORIAL</div>
        <h1><?php echo esc_html($institutional[0]); ?></h1>
    </div>

    <section class="about-grid">
        <div class="about-symbol" aria-hidden="true">
            <span class="til"></span>
        </div>
        <div class="about-copy">
            <p><?php echo esc_html($institutional[1]); ?></p>
        </div>
    </section>

    <?php foreach ([[2, 4], [4, 7], [7, 13]] as $range): ?>
        <?php [$start, $end] = $range; ?>
        <section class="identity-story">
            <h2><?php echo esc_html($institutional[$start]); ?></h2>
            <?php for ($i = $start + 1; $i < $end && isset($institutional[$i]); $i++): ?>
                <p><?php echo esc_html($institutional[$i]); ?></p>
            <?php endfor; ?>
        </section>
    <?php endforeach; ?>
</div>

<?php get_footer();
