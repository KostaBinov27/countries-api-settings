jQuery(document).ready(function($){

    async function getCachedData(){
        try{
            let cacheUrl = WPURLS.siteurl+'/wp-content/plugins/countries-api-settings/cache/cache.json';
            let res = await fetch(cacheUrl);
            return await res.json();
        } catch (error){
            return 0;
        }
    }

    async function renderCountries() {

        let container = document.querySelector('#countries');
        
        if (container !== null) {
            
            let countries = await getCachedData();

            countries = countries.data.countries;
            let html = '';

            countries.forEach(country => {
                let languagesAll = '';
                let languages = country.languages;
                languages.forEach(language => { languagesAll += language.name+', '; });
                let htmlSegment = `<div class="country">
                                    <div class="wrap">
                                        <h4>${country.name} <span>${country.emoji}</span></h4>
                                        <p><strong>Continent:</strong> <span>${country.continent.name}<span></p>
                                        <p><strong>Capital City:</strong> <span>${country.capital}<span></p>
                                        <p><strong>Native:</strong> <span>${country.native}<span></p>
                                        <p><strong>Language:</strong> <span>${languagesAll}<span></p>
                                        <p><strong>Currency:</strong> <span>${country.currency}<span></p>
                                    </div>
                                </div>`;
                html += htmlSegment;
            });

            container.innerHTML = html;
        }
    }
    
    renderCountries();

});
