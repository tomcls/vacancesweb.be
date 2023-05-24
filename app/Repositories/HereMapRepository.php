<?php

namespace App\Repositories;

use App\Data\Heremap\GeocodeData;
use App\Data\Heremap\SuggestionData;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;

use geoPHP;

class HereMapRepository
{
   public $reverseGeocodingUri = 'https://reverse.geocoder.ls.hereapi.com/6.2/reversegeocode.json';
   public $geocodingUri = "https://geocoder.ls.hereapi.com/6.2/geocode.json";
   public $autocompleteUri = "https://autocomplete.geocoder.ls.hereapi.com/6.2/suggest.json";
   private $apiKey = null;

   public function __construct()
   {
      $this->apiKey = env('HEREMAP_API_KEY');
   } 
   /**
    * Array filters
    * additionaldata=IncludeShapeLevel
    * language
    * searchtext
    */
   public function geocode(array $filters): GeocodeData
   {
      $client = new Client();
      $url = $this->geocodingUri . '?apiKey=' . $this->apiKey . '&searchtext=' . ($filters['searchtext']) . '&language=' . $filters['language'];
      $request = new Psr7Request('GET', $url);
      $promise = $client->sendAsync($request)->then(function ($response) {
         return json_decode($response->getBody(), 1);
      });
      $geocodeResult = $promise->wait();
      if (isset($geocodeResult['Response']['View'][0]['Result'][0])) {
         $response = $geocodeResult['Response']['View'][0]['Result'][0];
         $position = GeocodeData::from($response);
         return $position;
      }
      return null;
   }
   /**
    * language
    * query
    * rue des allies 201b, 1190 Bruxelles
    * rue%20des%20allies%20201b,%201190%20Bruxelles
    */
   public  function suggest(array $filters): array
   {

      $client = new Client();
      $url = $this->autocompleteUri . '?apiKey=' . $this->apiKey . '&query=' . ($filters['query']) . '&language=' . $filters['language'];
      $request = new Psr7Request('GET', $url);
      $promise = $client->sendAsync($request)->then(function ($response) {
         return json_decode($response->getBody(), 1);
      });
      $result = $promise->wait();
      $suggestions = [];
      foreach ($result['suggestions'] as $suggestion) {
         $hereMapSuggestion = SuggestionData::from($suggestion);
         $suggestions[] = $hereMapSuggestion;
      }
      return $suggestions;
   }

   /**
    * Array filters
    * additionaldata=IncludeShapeLevel
    * language
    * longitude
    * latitude  
    * $filters = ['prox'=>'50.849215483448000,4.364418990141500,250','mode'=>'retrieveAreas','maxResults'=>1,'language'=>'fr','additionaldata'=>'IncludeShapeLevel,State'];
    * example = https://reverse.geocoder.ls.hereapi.com/6.2/reversegeocode.json?prox=50.849215483448000,4.364418990141500,250&mode=retrieveAreas&maxresults=1&apiKey=1np7JUgnG7Qc0F4elHowNXvaeTg6DwATA74WUlx2t-A&language=fr&additionaldata=IncludeShapeLevel,State
    */
   public function reverse(array $filters): GeocodeData
   {
      $filters['apiKey'] = $this->apiKey;
      $queryString = http_build_query($filters);
      $client = new Client();
      $url = $this->reverseGeocodingUri . '?' . $queryString;
      $request = new Psr7Request('GET', $url);
      $promise = $client->sendAsync($request)->then(function ($response) {
         return json_decode($response->getBody(), 1);
      });
      $geocodeResult = $promise->wait();
      if (isset($geocodeResult['Response']['View'][0]['Result'][0])) {
         $response = $geocodeResult['Response']['View'][0]['Result'][0];
         $position = GeocodeData::from($response);
         return $position;
      }
      return null;
   }

   public function simplify($polygon, $level)
   {
      try {
         $geometry = geoPHP::load($polygon, 'wkt');
         $g = $geometry;
         if ($level === "country" ) {
            $g = $geometry->simplify(0.01);
         } elseif ($level === "state" ) {
            $g = $geometry->simplify(0.008);
         } elseif ($level == "county" ) {
            $g = $geometry->simplify(0.005);
         }elseif ( $level == "city") {
            $g = $geometry->simplify(0.0003);
         } elseif ($level == "postalCode" || $level == "district") {
            $g = $geometry->simplify(0.0003);
         }
         return $g;
      } catch (Exception $e) {
         logger($e->getMessage() . $e->getTraceAsString());
      }
      return null;
   }
}
/*
SUGGESTS




GEOCODE
example: rue des allies 201b 1190 Bruxelles
https://geocoder.ls.hereapi.com/6.2/geocode.json?searchtext=rue%20des%20allies%20201b%201190%20Forest&language=en&apiKey=1np7JUgnG7Qc0F4elHowNXvaeTg6DwATA74WUlx2t-A
{
   "Response":{
      "MetaInfo":{
         "Timestamp":"2023-03-26T16:15:34.580+0000"
      },
      "View":[
         {
            "_type":"SearchResultsViewType",
            "ViewId":0,
            "Result":[
               {
                  "Relevance":1.0,
                  "MatchLevel":"houseNumber",
                  "MatchQuality":{
                     "City":1.0,
                     "Street":[
                        1.0
                     ],
                     "HouseNumber":1.0,
                     "PostalCode":1.0
                  },
                  "MatchType":"pointAddress",
                  "Location":{
                     "LocationId":"NT_TQ5PWd-xUBugClVvok9pvD_yATMCB",
                     "LocationType":"point",
                     "DisplayPosition":{
                        "Latitude":50.82049,
                        "Longitude":4.3274
                     },
                     "NavigationPosition":[
                        {
                           "Latitude":50.82049,
                           "Longitude":4.32712
                        }
                     ],
                     "MapView":{
                        "TopLeft":{
                           "Latitude":50.8216142,
                           "Longitude":4.3256206
                        },
                        "BottomRight":{
                           "Latitude":50.8193658,
                           "Longitude":4.3291794
                        }
                     },
                     "Address":{
                        "Label":"Rue des Alliés 201B, 1190 Forest, Belgium",
                        "Country":"BEL",
                        "State":"Brussels",
                        "County":"Brussels",
                        "City":"Forest",
                        "Street":"Rue des Alliés",
                        "HouseNumber":"201B",
                        "PostalCode":"1190",
                        "AdditionalData":[
                           {
                              "value":"Belgium",
                              "key":"CountryName"
                           },
                           {
                              "value":"Brussels",
                              "key":"StateName"
                           },
                           {
                              "value":"Brussels",
                              "key":"CountyName"
                           }
                        ]
                     }
                  }
               }
            ]
         }
      ]
   }
}

*/