<?php
function fetch_cities(){
    $url = "http://polen.sepa.gov.rs/api/opendata/locations/";
    $resp = file_get_contents($url);
    if ($resp === false){
        return "Error";
    }
    else{
        return $decoded = json_decode($resp,true);
    }
}
/*function fetch_polen($city, $date_after, $date_before) {
    $url_polen = "http://polen.sepa.gov.rs/api/opendata/pollens/?location=$city&date_after=$date_after&date_before=$date_before";
    $resp = file_get_contents($url_polen);
    if ($resp === false) {
        return "Error fetching pollen data";
    } else {
        $decoded_polen = json_decode($resp, true);
        if (isset($decoded_polen['results']) && is_array($decoded_polen['results'])) {
            $allergensMap = [];

            foreach ($decoded_polen['results'] as $item) {
                $date = $item['date'];
                $allergens = [];

                if (isset($item['concentrations']) && is_array($item['concentrations'])) {
                    foreach ($item['concentrations'] as $con) {
                        $url_concentrations = "http://polen.sepa.gov.rs/api/opendata/concentrations/$con";
                        $resp_concentrations = file_get_contents($url_concentrations);

                        if ($resp_concentrations === false) {
                            return "Error fetching concentration data";
                        } else {
                            $decoded_concentrations = json_decode($resp_concentrations, true);

                            if (isset($decoded_concentrations['allergen'])) {
                                $allergenId = $decoded_concentrations['allergen'];
                                $url_allergen = "http://polen.sepa.gov.rs/api/opendata/allergens/$allergenId";
                                $resp_allergen = file_get_contents($url_allergen);

                                if ($resp_allergen === false) {
                                    return "Error fetching allergen data";
                                } else {
                                    $decoded_allergen = json_decode($resp_allergen, true);
                                    $allergens[] = $decoded_allergen;
                                }
                            }
                        }
                    }
                }
                $allergensMap[$date] = $allergens;
            }
            return $allergensMap;
        }
        else {
            return "No results found";
        }
    }
}*/
function fetch_polen($city, $date_after, $date_before) {
    $url_polen = "http://polen.sepa.gov.rs/api/opendata/pollens/?location=$city&date_after=$date_after&date_before=$date_before";
    $resp = file_get_contents($url_polen);

    if ($resp === false) {
        return "Error fetching pollen data";
    } else {
        $decoded_polen = json_decode($resp, true);

        if (isset($decoded_polen['results']) && is_array($decoded_polen['results'])) {
            $allergensMap = [];

            foreach ($decoded_polen['results'] as $item) {
                $date = $item['date'];
                $allergens = [];

                if (isset($item['concentrations']) && is_array($item['concentrations'])) {
                    foreach ($item['concentrations'] as $con) {
                        $url_concentrations = "http://polen.sepa.gov.rs/api/opendata/concentrations/$con";
                        $resp_concentrations = file_get_contents($url_concentrations);

                        if ($resp_concentrations === false) {
                            return "Error fetching concentration data";
                        } else {
                            $decoded_concentrations = json_decode($resp_concentrations, true);

                            if (isset($decoded_concentrations['allergen'])) {
                                $allergenId = $decoded_concentrations['allergen'];
                                $url_allergen = "http://polen.sepa.gov.rs/api/opendata/allergens/$allergenId";
                                $resp_allergen = file_get_contents($url_allergen);

                                if ($resp_allergen === false) {
                                    return "Error fetching allergen data";
                                } else {
                                    $decoded_allergen = json_decode($resp_allergen, true);
                                    $translatedAllergen = translateToSerbian($decoded_allergen);

                                    $allergens[] = $translatedAllergen;
                                }
                            }
                        }
                    }
                }

                $allergensMap[$date] = $allergens;
            }

            return $allergensMap;
        } else {
            return "No results found";
        }
    }
}

function translateToSerbian($allergenData) {
    $translations = [
        "low" => "nisko",
        "medium" => "srednje",
        "high" => "visoko",
        "moderate" => "umereno",
        "mild" => "blago"
    ];

    if (isset($allergenData['allergenicity_display']) && isset($translations[$allergenData['allergenicity_display']])) {
        $allergenData['allergenicity_display_serbian'] = $translations[$allergenData['allergenicity_display']];
    }

    return $allergenData;
}




