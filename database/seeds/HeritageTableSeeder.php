<?php

use Illuminate\Database\Seeder;
use App\Heritage;

class HeritageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $heritage = Heritage::create(
        	['asset_name' => '1.jpg',
        	'asset_type' => 'image',
        	'description' => 'King Leopold and Princess Lilian',
        	'happened_at' => '1942-01-01',
        	]);
        $heritage->categories()->sync([1]);

        $heritage = Heritage::create(
        	[
        	'asset_name' => '2.jpg',
        	'asset_type' => 'image',
        	'description' => 'Two German motorcyclists armed with MP 40s follow a King Tiger',
        	'happened_at' => 'december 1944',
        	]);
        $heritage->categories()->sync([1]);
        
        $heritage = Heritage::create(
        	[
        	'asset_name' => '3.jpg',
        	'asset_type' => 'image',
        	'description' => 'Infantrymen in the South Saskatchewan Regiment',
        	'happened_at' => 'september 1944',
        	]);
        $heritage->categories()->sync([1]);
        
        $heritage = Heritage::create(
        	[
        	'asset_name' => '4.jpg',
        	'asset_type' => 'image',
        	'description' => 'Tramway in Borinage',
        	'happened_at' => '1959',
        	]);
        $heritage->categories()->sync([2]);
        
        $heritage = Heritage::create(
        	[
        	'asset_name' => '5.jpg',
        	'asset_type' => 'image',
        	'description' => 'Graslei Gent',
        	'happened_at' => '1954',
        	]);
        $heritage->categories()->sync([2]);
        
        $heritage = Heritage::create(
        	[
        	'asset_name' => '6.jpg',
        	'asset_type' => 'image',
        	'description' => 'Mode in de jaren 60',
        	'happened_at' => '1960',
        	]);
        $heritage->categories()->sync([3]);
        
        $heritage = Heritage::create(
        	[
        	'asset_name' => '7.jpg',
        	'asset_type' => 'image',
        	'description' => 'Mars tegen de oorlog in Vietnam',
        	'happened_at' => '1964',
        	]);
        $heritage->categories()->sync([3]);
        
        $heritage = Heritage::create(
        	[
        	'asset_name' => '8.jpg',
        	'asset_type' => 'image',
        	'description' => 'De rede van bij elkaar 17 minuten hield Martin Luther King voor het Lincoln Memorial in Washington D.C. De woorden van King inspireerden niet alleen de bezoekers ter plekke, maar ook elders in de wereld en in latere tijden. De rede hield hij ter gelegenheid van de Mars naar Washington die die dag in de stad was aangekomen, ten overstaan van een publiek van meer dan 200.000 mensen. Van de rede werd rechtstreeks verslag uitgebracht op televisie.',
        	'happened_at' => '28 augustus 1963',
        	]);
        $heritage->categories()->sync([3]);
        
        $heritage = Heritage::create(
        	[
        	'asset_name' => '9.jpg',
        	'asset_type' => 'image',
        	'description' => 'Moonlanding in 1969. Aldrin next to the Passive Seismic Experiment Package with Eagle in the background',
        	'happened_at' => '20 juli 1969',
        	]);
        $heritage->categories()->sync([3]);
        
        $heritage = Heritage::create(
        	[
        	'asset_name' => '10.jpg',
        	'asset_type' => 'image',
        	'description' => 'Affiche naar aanleiding van de Rodenbachfeesten van 21 tot 23 augustus en 12 september 1909 met onder meer liederavond, onthulling standbeeld, cantate, stoet, vuurwerk, studentenlanddag, toneel, feest voor Ernest Van Dyck, ... uitgevaardigd door het stadsbestuur en het feestcomité. De affiche toont de afbeelding van een vrouw in geel gewaad met een Vlaamse leeuw op de borst. zij staat aan het roer van een schip met opschrift vliegt de blauwvoet. Voor haar staat een engel. Op de achtergrond de skyline van Roeselare. De affiche is getekend door Gaston Vallaeys.',
        	'happened_at' => 'augustus/september 1909',
        	]);
        $heritage->categories()->sync([4,9]);
        
        $heritage = Heritage::create(
        	[
        	'asset_name' => '11.jpg',
        	'asset_type' => 'image',
        	'description' => 'Opening sportstadion Ingelmunster',
        	'happened_at' => '1978',
        	]);
        $heritage->categories()->sync([5]);
        
        $heritage = Heritage::create(
        	[
        	'asset_name' => '12.jpg',
        	'asset_type' => 'image',
        	'description' => 'Wielerwedstrijd Brussel-Izegem gewonnen door L. Vandaele. Deze foto maakt deel uit van de collectie van persfotograaf Maurice Terryn, die Izegem portretteerde vanaf de jaren 1950 tot eind de jaren 1990. De volledige collectie (meer dan 35.000 negatieven) werd door Stad Izegem aangekocht.',
        	'happened_at' => '1968',
        	]);
        $heritage->categories()->sync([3, 5]);
        
        $heritage = Heritage::create(
        	[
        	'asset_name' => '13.jpg',
        	'asset_type' => 'image',
        	'description' => 'Bezoek Koning Boudewijn en Koningin Fabiola',
        	'happened_at' => '3 juli 1964',
        	]);
        $heritage->categories()->sync([3,6]);
        
        $heritage = Heritage::create(
        	[
        	'asset_name' => '14.jpg',
        	'asset_type' => 'image',
        	'description' => 'Koning Boudewijn en koningin Fabiola op bezoek in Roeselare. Koning Boudewijn en koningin Fabiola begroeten het volk op de Grote Markt. In het midden staat ondercommissaris Maurits Seurinck. De dame met het fototoestel is Lucie Cracco.',
        	'happened_at' => '',
        	]);
        $heritage->categories()->sync([6,9]);
        
        $heritage = Heritage::create(
        	[
        	'asset_name' => '15.jpg',
        	'asset_type' => 'image',
        	'description' => 'Carosserie Desot gelegen langs de Bruggesteenweg te Gits kreeg koningklijk bezoek en mocht Koning Bouwdewijn ontvangen tijdens een bedrijfsbezoek te Gits in 1990. Roger Storme, een werknemer van het bedrijf, kreeg de eer om de koning te ontvangen en hem een boek over het bedrijf te overhandigen.',
        	'happened_at' => '1990',
        	]);
        $heritage->categories()->sync([6]);
        
        $heritage = Heritage::create(
        	[
        	'asset_name' => '16.jpg',
        	'asset_type' => 'image',
        	'description' => 'Godshuis Lichtervelde. Doordat tijdens de Eerste Wereldoorlog de klaslokalen werden opgeëist door de Duitsers, kregen de jongens in Lichtervelde les in kamertjes van het godshuis.',
        	'happened_at' => '1968',
        	]);
        $heritage->categories()->sync([3,7]);
        
        $heritage = Heritage::create(
        	[
        	'asset_name' => '17.jpg',
        	'asset_type' => 'image',
        	'description' => 'De foto geeft een mooi beeld van de Markt met kerk in Moorslede in februari 1917.  Rechts op de achtergrond staat het gemeentehuis van tijdens de Eerste Wereldoorlog (wit gebouw met torentje).',
        	'happened_at' => 'februari 1917',
        	]);
        $heritage->categories()->sync([7]);
        
        $heritage = Heritage::create(
        	[
        	'asset_name' => '18.jpg',
        	'asset_type' => 'image',
        	'description' => 'Postkaart van de Hofmolen te Lichtervelde uitgegeven door Sintobin-Yperman.  Deze molen was wellicht de oudste van Lichtervelde en verdween toen hij helemaal op het einde van de Eerste Wereldoorlog opgeblazen werd door de wegtrekkende Duitse troepen.  De laatste uitbater was Boutte, vandaar dat men ook spreekt van Bouttens molen.',
        	'happened_at' => '1954',
        	]);
        $heritage->categories()->sync([2,7]);

        $heritage = Heritage::create(
        	[
        	'asset_name' => '19.jpg',
        	'asset_type' => 'image',
        	'description' => 'Jules Mestdagh, geboren 1 oktober 1894, werd soldaat in Lier. Daar kreeg hij opleiding bij de Grenadiers 1ste bataljon, vierde compagnie. Daar is hij bijgebleven voor zijn hele legerdienst. Hij kreeg hiervoor een erediploma van de gemeente Ingelmunster.',
        	'happened_at' => '1914-1918',
        	]);
        $heritage->categories()->sync([8]);
        	
        $heritage = Heritage::create([
        	'asset_name' => '20.jpg',
        	'asset_type' => 'image',
        	'description' => 'Portretfoto van Ortskommandant major Prasse van Roeselare. ',
        	'happened_at' => '1914-1918',
        	]);
        $heritage->categories()->sync([8,9]);

        $heritage = Heritage::create([
            'asset_name' => '21.jpg',
            'asset_type' => 'image',
            'description' => 'Pietro Ferrero maakte een pasta bestaande uit hazelnoten, suiker en een snuifje cacao. Dit werd uitgebracht onder de naam SuperCrema, nu beter bekend als Nutella.',
            'happened_at' => '14 mei 1946',
            ]);
        $heritage->categories()->sync([1,10]);

        $heritage = Heritage::create([
            'asset_name' => '22.jpg',
            'asset_type' => 'image',
            'description' => 'Het eerste McDonald fastfoodketen restaurant werd geopend naast de Monrovia luchthaven in LA, US',
            'happened_at' => '1937',
            ]);
        $heritage->categories()->sync([10]);

        $heritage = Heritage::create([
            'asset_name' => '23.jpg',
            'asset_type' => 'image',
            'description' => 'Camille Wyckmans maakte de eerste borstbollen op basis van een regionaal recept. Aanvankelijk verkocht hij ze enkel thuis in zijn eigen kruidenierswinkel maar dit werd vlug uitgebreid naar grotere locaties.',
            'happened_at' => '1946',
            ]);
        $heritage->categories()->sync([1,10]);
    }
}
