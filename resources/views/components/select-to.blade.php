@props(['field', 'options', 'selected' => ''])
<div x-data="data()">
    <input type="hidden" :value="selected.value">
    <input type="text" x-model="search" placeholder="Click to search..." @click="optionsVisible = !optionsVisible">
    <div x-show="optionsVisible">
        <template x-for="option in filteredOptions()">
            <a @click.prevent="selected = option; optionsVisible = false" x-text="option.label" style="display: block;"></a>
        </template>
    </div>
    <p>Current search: <span x-text="search"></span></p>
    <p>Selected option: <span x-text="selected.label"></span></p>
</div>
</div>

<script>
    function data() {
    return {
        optionsVisible: false,
        search: "",
        selected: {
            label: "",
            value: ""
        },
        options: [
            {
                label: "Abattoir Worker",
                value: "abattoir worker"
            },
            {
                label: "Abbot",
                value: "abbot"
            },
            {
                label: "Abstractor",
                value: "abstractor"
            },
            {
                label: "Accommodation Officer",
                value: "accommodation officer"
            },
            {
                label: "Accompanist",
                value: "accompanist"
            },
            {
                label: "Account Director",
                value: "account director"
            },
            {
                label: "Account Executive",
                value: "account executive"
            },
            {
                label: "Account Manager",
                value: "account manager"
            },
            {
                label: "Accountant",
                value: "accountant"
            },
            {
                label: "Accountant - Chartered or Certified",
                value: "accountant - chartered or certified"
            },
            {
                label: "Accounts Assistant",
                value: "accounts assistant"
            },
            {
                label: "Accounts Clerk",
                value: "accounts clerk"
            },
            {
                label: "Accounts Manager",
                value: "accounts manager"
            },
            {
                label: "Accounts Staff",
                value: "accounts staff"
            },
            {
                label: "Acoustic Engineer",
                value: "acoustic engineer"
            },
            {
                label: "Acrobat",
                value: "acrobat"
            },
            {
                label: "Actor",
                value: "actor"
            },
            {
                label: "Actor / Actress",
                value: "actor / actress"
            },
            {
                label: "Actress",
                value: "actress"
            },
            {
                label: "Actuary",
                value: "actuary"
            },
            {
                label: "Acupuncturist",
                value: "acupuncturist"
            },
            {
                label: "Administration Assistant",
                value: "administration assistant"
            },
            {
                label: "Administration Clerk",
                value: "administration clerk"
            },
            {
                label: "Administration Manager",
                value: "administration manager"
            },
            {
                label: "Administration Officer",
                value: "administration officer"
            },
            {
                label: "Administration Staff",
                value: "administration staff"
            },
            {
                label: "Administrator",
                value: "administrator"
            },
            {
                label: "Advertising Agent",
                value: "advertising agent"
            },
            {
                label: "Advertising Assistant",
                value: "advertising assistant"
            },
            {
                label: "Advertising Buyer",
                value: "advertising buyer"
            },
            {
                label: "Advertising Clerk",
                value: "advertising clerk"
            },
            {
                label: "Advertising Contractor",
                value: "advertising contractor"
            },
            {
                label: "Advertising Controller",
                value: "advertising controller"
            },
            {
                label: "Advertising Director",
                value: "advertising director"
            },
            {
                label: "Advertising Executive",
                value: "advertising executive"
            },
            {
                label: "Advertising Manager",
                value: "advertising manager"
            },
            {
                label: "Advertising Staff",
                value: "advertising staff"
            },
            {
                label: "Advocate",
                value: "advocate"
            },
            {
                label: "Aerial Erector",
                value: "aerial erector"
            },
            {
                label: "Aerobic / Keep Fit Instructor",
                value: "aerobic / keep fit instructor"
            },
            {
                label: "Aerobic Instructor",
                value: "aerobic instructor"
            },
            {
                label: "Aeronautical Engineer",
                value: "aeronautical engineer"
            },
            {
                label: "Agister",
                value: "agister"
            },
            {
                label: "Agricultural Consultant",
                value: "agricultural consultant"
            },
            {
                label: "Agricultural Contractor",
                value: "agricultural contractor"
            },
            {
                label: "Agricultural Engineer",
                value: "agricultural engineer"
            },
            {
                label: "Agricultural Merchant",
                value: "agricultural merchant"
            },
            {
                label: "Agricultural Worker",
                value: "agricultural worker"
            },
            {
                label: "Agriculturalist",
                value: "agriculturalist"
            },
            {
                label: "Agronomist",
                value: "agronomist"
            },
            {
                label: "Air Force - NCO / Commissioned Officer",
                value: "air force - nco / commissioned officer"
            },
            {
                label: "Air Force - Other Ranks",
                value: "air force - other ranks"
            },
            {
                label: "Air Traffic Controller",
                value: "air traffic controller"
            },
            {
                label: "Aircraft - Flight Deck Crew",
                value: "aircraft - flight deck crew"
            },
            {
                label: "Aircraft Buyer",
                value: "aircraft buyer"
            },
            {
                label: "Aircraft Cabin Crew",
                value: "aircraft cabin crew"
            },
            {
                label: "Aircraft Designer",
                value: "aircraft designer"
            },
            {
                label: "Aircraft Engineer",
                value: "aircraft engineer"
            },
            {
                label: "Aircraft Fitter",
                value: "aircraft fitter"
            },
            {
                label: "Aircraft Hand",
                value: "aircraft hand"
            },
            {
                label: "Aircraft Maintenance Engineer",
                value: "aircraft maintenance engineer"
            },
            {
                label: "Aircraft Surface Finisher",
                value: "aircraft surface finisher"
            },
            {
                label: "Airline Broker",
                value: "airline broker"
            },
            {
                label: "Airline Check-in Staff",
                value: "airline check-in staff"
            },
            {
                label: "Airline Employee - Airport",
                value: "airline employee - airport"
            },
            {
                label: "Airline Employee - Non-Airport",
                value: "airline employee - non-airport"
            },
            {
                label: "Airman",
                value: "airman"
            },
            {
                label: "Airport Controller",
                value: "airport controller"
            },
            {
                label: "Airport Manager",
                value: "airport manager"
            },
            {
                label: "Alarm Fitter",
                value: "alarm fitter"
            },
            {
                label: "Almoner",
                value: "almoner"
            },
            {
                label: "Ambulance Controller",
                value: "ambulance controller"
            },
            {
                label: "Ambulance Crew",
                value: "ambulance crew"
            },
            {
                label: "Ambulance Driver",
                value: "ambulance driver"
            },
            {
                label: "Amusement Arcade Worker",
                value: "amusement arcade worker"
            },
            {
                label: "Anaesthetist",
                value: "anaesthetist"
            },
            {
                label: "Analytical Chemist",
                value: "analytical chemist"
            },
            {
                label: "Animal Breeder",
                value: "animal breeder"
            },
            {
                label: "Animator",
                value: "animator"
            },
            {
                label: "Announcer",
                value: "announcer"
            },
            {
                label: "Anthropologist",
                value: "anthropologist"
            },
            {
                label: "Antique Dealer",
                value: "antique dealer"
            },
            {
                label: "Antique Renovator",
                value: "antique renovator"
            },
            {
                label: "Appeal / Tribunal Chairman",
                value: "appeal / tribunal chairman"
            },
            {
                label: "Applications Engineer",
                value: "applications engineer"
            },
            {
                label: "Applications Programmer",
                value: "applications programmer"
            },
            {
                label: "Apprentice",
                value: "apprentice"
            },
            {
                label: "Aquarist",
                value: "aquarist"
            },
            {
                label: "Arbitrator",
                value: "arbitrator"
            },
            {
                label: "Arborist",
                value: "arborist"
            },
            {
                label: "Archaeologist",
                value: "archaeologist"
            },
            {
                label: "Archbishop",
                value: "archbishop"
            },
            {
                label: "ArchDeacon",
                value: "archdeacon"
            },
            {
                label: "Architect",
                value: "architect"
            },
            {
                label: "Architects Technician",
                value: "architects technician"
            },
            {
                label: "Architectural Surveyor",
                value: "architectural surveyor"
            },
            {
                label: "Archivist",
                value: "archivist"
            },
            {
                label: "Area Manager",
                value: "area manager"
            },
            {
                label: "Armourer",
                value: "armourer"
            },
            {
                label: "Army - NCO / Commissioned Officer",
                value: "army - nco / commissioned officer"
            },
            {
                label: "Army - Other Ranks",
                value: "army - other ranks"
            },
            {
                label: "Aromatherapist",
                value: "aromatherapist"
            },
            {
                label: "Art Buyer",
                value: "art buyer"
            },
            {
                label: "Art Critic",
                value: "art critic"
            },
            {
                label: "Art Dealer",
                value: "art dealer"
            },
            {
                label: "Art Director",
                value: "art director"
            },
            {
                label: "Art Historian",
                value: "art historian"
            },
            {
                label: "Art Restorer",
                value: "art restorer"
            },
            {
                label: "Artexer",
                value: "artexer"
            },
            {
                label: "Articled Clerk",
                value: "articled clerk"
            },
            {
                label: "Artificial Inseminator",
                value: "artificial inseminator"
            },
            {
                label: "Artificial Limb Fitter",
                value: "artificial limb fitter"
            },
            {
                label: "Artist",
                value: "artist"
            },
            {
                label: "Artist - Commercial",
                value: "artist - commercial"
            },
            {
                label: "Artist - Freelance",
                value: "artist - freelance"
            },
            {
                label: "Artist - Portrait",
                value: "artist - portrait"
            },
            {
                label: "Artist - Technical",
                value: "artist - technical"
            },
            {
                label: "Asbestos Remover",
                value: "asbestos remover"
            },
            {
                label: "Asphalter",
                value: "asphalter"
            },
            {
                label: "Asphalter / Roadworker",
                value: "asphalter / roadworker"
            },
            {
                label: "Assembly Worker",
                value: "assembly worker"
            },
            {
                label: "Assessor",
                value: "assessor"
            },
            {
                label: "Assistant Accounts Manager",
                value: "assistant accounts manager"
            },
            {
                label: "Assistant Caretaker",
                value: "assistant caretaker"
            },
            {
                label: "Assistant Cook",
                value: "assistant cook"
            },
            {
                label: "Assistant Manager",
                value: "assistant manager"
            },
            {
                label: "Assistant Nurse",
                value: "assistant nurse"
            },
            {
                label: "Assistant Teacher",
                value: "assistant teacher"
            },
            {
                label: "Associate Director",
                value: "associate director"
            },
            {
                label: "Astrologer",
                value: "astrologer"
            },
            {
                label: "Astronomer",
                value: "astronomer"
            },
            {
                label: "Athlete",
                value: "athlete"
            },
            {
                label: "Au Pair",
                value: "au pair"
            },
            {
                label: "Auction Worker",
                value: "auction worker"
            },
            {
                label: "Auctioneer",
                value: "auctioneer"
            },
            {
                label: "Audiologist",
                value: "audiologist"
            },
            {
                label: "Audit Clerk",
                value: "audit clerk"
            },
            {
                label: "Audit Manager",
                value: "audit manager"
            },
            {
                label: "Auditor",
                value: "auditor"
            },
            {
                label: "Author",
                value: "author"
            },
            {
                label: "Auto Electrician",
                value: "auto electrician"
            },
            {
                label: "Auxiliary Nurse",
                value: "auxiliary nurse"
            },
            {
                label: "Bacon Curer",
                value: "bacon curer"
            },
            {
                label: "Bacteriologist",
                value: "bacteriologist"
            },
            {
                label: "Baggage Handler",
                value: "baggage handler"
            },
            {
                label: "Bailiff",
                value: "bailiff"
            },
            {
                label: "Baker",
                value: "baker"
            },
            {
                label: "Bakery Assistant",
                value: "bakery assistant"
            },
            {
                label: "Bakery Manager",
                value: "bakery manager"
            },
            {
                label: "Bakery Operator",
                value: "bakery operator"
            },
            {
                label: "Ballistics Expert",
                value: "ballistics expert"
            },
            {
                label: "Balloonist",
                value: "balloonist"
            },
            {
                label: "Bank Clerk",
                value: "bank clerk"
            },
            {
                label: "Bank Manager",
                value: "bank manager"
            },
            {
                label: "Bank Messenger",
                value: "bank messenger"
            },
            {
                label: "Bank Note Checker",
                value: "bank note checker"
            },
            {
                label: "Bank Staff",
                value: "bank staff"
            },
            {
                label: "Banking Correspondent",
                value: "banking correspondent"
            },
            {
                label: "Baptist Minister",
                value: "baptist minister"
            },
            {
                label: "Bar Manager",
                value: "bar manager"
            },
            {
                label: "Bar Staff",
                value: "bar staff"
            },
            {
                label: "Bar Steward",
                value: "bar steward"
            },
            {
                label: "Barber",
                value: "barber"
            },
            {
                label: "Bargeman",
                value: "bargeman"
            },
            {
                label: "Barmaid",
                value: "barmaid"
            },
            {
                label: "Barman",
                value: "barman"
            },
            {
                label: "Barrister",
                value: "barrister"
            },
            {
                label: "Basket Worker",
                value: "basket worker"
            },
            {
                label: "BBC Employee - Clerical",
                value: "bbc employee - clerical"
            },
            {
                label: "BBC Stage Mover",
                value: "bbc stage mover"
            },
            {
                label: "Beautician",
                value: "beautician"
            },
            {
                label: "Beauty Therapist",
                value: "beauty therapist"
            },
            {
                label: "Beekeeper",
                value: "beekeeper"
            },
            {
                label: "Betting Shop Clerk",
                value: "betting shop clerk"
            },
            {
                label: "Bill Poster",
                value: "bill poster"
            },
            {
                label: "Bingo Caller",
                value: "bingo caller"
            },
            {
                label: "Bingo Hall Staff",
                value: "bingo hall staff"
            },
            {
                label: "Biochemist",
                value: "biochemist"
            },
            {
                label: "Biologist",
                value: "biologist"
            },
            {
                label: "Biometrician",
                value: "biometrician"
            },
            {
                label: "Biophysicist",
                value: "biophysicist"
            },
            {
                label: "Bishop",
                value: "bishop"
            },
            {
                label: "Blacksmith",
                value: "blacksmith"
            },
            {
                label: "Blind Assembler",
                value: "blind assembler"
            },
            {
                label: "Blind Fitter",
                value: "blind fitter"
            },
            {
                label: "Blinds Installer",
                value: "blinds installer"
            },
            {
                label: "Boat Builder",
                value: "boat builder"
            },
            {
                label: "Boatswain",
                value: "boatswain"
            },
            {
                label: "Body Fitter",
                value: "body fitter"
            },
            {
                label: "Bodyguard",
                value: "bodyguard"
            },
            {
                label: "Bodyshop Manager",
                value: "bodyshop manager"
            },
            {
                label: "Boiler Maker",
                value: "boiler maker"
            },
            {
                label: "Boiler Man",
                value: "boiler man"
            },
            {
                label: "Book Binder",
                value: "book binder"
            },
            {
                label: "Book Seller",
                value: "book seller"
            },
            {
                label: "Book Finisher",
                value: "book finisher"
            },
            {
                label: "Booking Agent",
                value: "booking agent"
            },
            {
                label: "Booking Clerk",
                value: "booking clerk"
            },
            {
                label: "Booking Office Clerk",
                value: "booking office clerk"
            },
            {
                label: "Book-Keeper",
                value: "book-keeper"
            },
            {
                label: "Bookmaker",
                value: "bookmaker"
            },
            {
                label: "Boom Operator",
                value: "boom operator"
            },
            {
                label: "Botanist",
                value: "botanist"
            },
            {
                label: "Bottler",
                value: "bottler"
            },
            {
                label: "Box Maker",
                value: "box maker"
            },
            {
                label: "Box Office Clerk",
                value: "box office clerk"
            },
            {
                label: "Branch Manager",
                value: "branch manager"
            },
            {
                label: "Breeder",
                value: "breeder"
            },
            {
                label: "Brewer",
                value: "brewer"
            },
            {
                label: "Brewery Manager",
                value: "brewery manager"
            },
            {
                label: "Brewery Worker",
                value: "brewery worker"
            },
            {
                label: "Bricklayer",
                value: "bricklayer"
            },
            {
                label: "Bridgeman",
                value: "bridgeman"
            },
            {
                label: "Bridgemaster",
                value: "bridgemaster"
            },
            {
                label: "Broadcaster",
                value: "broadcaster"
            },
            {
                label: "Broadcaster - TV / Radio",
                value: "broadcaster - tv / radio"
            },
            {
                label: "Broadcasting Engineer",
                value: "broadcasting engineer"
            },
            {
                label: "Brother (Church)",
                value: "brother (church)"
            },
            {
                label: "Builder",
                value: "builder"
            },
            {
                label: "Builders Labourer",
                value: "builders labourer"
            },
            {
                label: "Builders Merchant",
                value: "builders merchant"
            },
            {
                label: "Building Advisor",
                value: "building advisor"
            },
            {
                label: "Building Contractor",
                value: "building contractor"
            },
            {
                label: "Building Control Officer",
                value: "building control officer"
            },
            {
                label: "Building Engineer",
                value: "building engineer"
            },
            {
                label: "Building Estimator",
                value: "building estimator"
            },
            {
                label: "Building Foreman",
                value: "building foreman"
            },
            {
                label: "Building Inspector",
                value: "building inspector"
            },
            {
                label: "Building Maintenance",
                value: "building maintenance"
            },
            {
                label: "Building Manager",
                value: "building manager"
            },
            {
                label: "Building Site Inspector",
                value: "building site inspector"
            },
            {
                label: "Building Society Agent",
                value: "building society agent"
            },
            {
                label: "Building Society Clerk",
                value: "building society clerk"
            },
            {
                label: "Building Society Staff",
                value: "building society staff"
            },
            {
                label: "Building Surveyor",
                value: "building surveyor"
            },
            {
                label: "Bursar",
                value: "bursar"
            },
            {
                label: "Bus Company Employee",
                value: "bus company employee"
            },
            {
                label: "Bus Conductor",
                value: "bus conductor"
            },
            {
                label: "Bus Driver",
                value: "bus driver"
            },
            {
                label: "Bus Mechanic",
                value: "bus mechanic"
            },
            {
                label: "Bus Valeter",
                value: "bus valeter"
            },
            {
                label: "Business Analyst",
                value: "business analyst"
            },
            {
                label: "Business Consultant",
                value: "business consultant"
            },
            {
                label: "Business Proprietor",
                value: "business proprietor"
            },
            {
                label: "Business Proprietor - Licensed Premises",
                value: "business proprietor - licensed premises"
            },
            {
                label: "Butcher",
                value: "butcher"
            },
            {
                label: "Butchery Manager",
                value: "butchery manager"
            },
            {
                label: "Butler",
                value: "butler"
            },
            {
                label: "Buyer",
                value: "buyer"
            },
            {
                label: "Cabinet Maker",
                value: "cabinet maker"
            },
            {
                label: "Cable Contractor",
                value: "cable contractor"
            },
            {
                label: "Cable Jointer",
                value: "cable jointer"
            },
            {
                label: "Cable TV Installer",
                value: "cable tv installer"
            },
            {
                label: "Cafe Owner",
                value: "cafe owner"
            },
            {
                label: "Cafe Staff",
                value: "cafe staff"
            },
            {
                label: "Cafe Worker",
                value: "cafe worker"
            },
            {
                label: "Calibration Manager",
                value: "calibration manager"
            },
            {
                label: "Call Centre Manager",
                value: "call centre manager"
            },
            {
                label: "Call Centre Staff",
                value: "call centre staff"
            },
            {
                label: "Calligrapher",
                value: "calligrapher"
            },
            {
                label: "Camera Repairer",
                value: "camera repairer"
            },
            {
                label: "Cameraman",
                value: "cameraman"
            },
            {
                label: "Cameraman - TV / Films",
                value: "cameraman - tv / films"
            },
            {
                label: "Canal Boat Broker",
                value: "canal boat broker"
            },
            {
                label: "Canon (Church)",
                value: "canon (church)"
            },
            {
                label: "Canvasser",
                value: "canvasser"
            },
            {
                label: "Captain Merchant Ship",
                value: "captain merchant ship"
            },
            {
                label: "Car Body Repairer",
                value: "car body repairer"
            },
            {
                label: "Car Builder",
                value: "car builder"
            },
            {
                label: "Car Dealer",
                value: "car dealer"
            },
            {
                label: "Car Delivery Driver",
                value: "car delivery driver"
            },
            {
                label: "Car Park Attendant",
                value: "car park attendant"
            },
            {
                label: "Car Salesman",
                value: "car salesman"
            },
            {
                label: "Car Valet",
                value: "car valet"
            },
            {
                label: "Car Wash Attendant",
                value: "car wash attendant"
            },
            {
                label: "Cardinal",
                value: "cardinal"
            },
            {
                label: "Cardiographer",
                value: "cardiographer"
            },
            {
                label: "Cardiologist",
                value: "cardiologist"
            },
            {
                label: "Care Assistant",
                value: "care assistant"
            },
            {
                label: "Care Manager",
                value: "care manager"
            },
            {
                label: "Careers Advisor",
                value: "careers advisor"
            },
            {
                label: "Careers Officer",
                value: "careers officer"
            },
            {
                label: "Carer - Non Professional",
                value: "carer - non professional"
            },
            {
                label: "Carer - Professional",
                value: "carer - professional"
            },
            {
                label: "Caretaker",
                value: "caretaker"
            },
            {
                label: "Cargo Handler",
                value: "cargo handler"
            },
            {
                label: "Cargo Operator",
                value: "cargo operator"
            },
            {
                label: "Carpenter",
                value: "carpenter"
            },
            {
                label: "Carpenters Assistant",
                value: "carpenters assistant"
            },
            {
                label: "Carpet Cleaner",
                value: "carpet cleaner"
            },
            {
                label: "Carpet Fitter",
                value: "carpet fitter"
            },
            {
                label: "Carpet Retailer",
                value: "carpet retailer"
            },
            {
                label: "Carpet Fitter",
                value: "carpet fitter"
            },
            {
                label: "Carphone Fitter",
                value: "carphone fitter"
            },
            {
                label: "Cartographer",
                value: "cartographer"
            },
            {
                label: "Cartoonist",
                value: "cartoonist"
            },
            {
                label: "Cash Point Fitter",
                value: "cash point fitter"
            },
            {
                label: "Cashier",
                value: "cashier"
            },
            {
                label: "Casual Worker",
                value: "casual worker"
            },
            {
                label: "Cataloguer",
                value: "cataloguer"
            },
            {
                label: "Caterer",
                value: "caterer"
            },
            {
                label: "Catering Consultant",
                value: "catering consultant"
            },
            {
                label: "Catering Manager",
                value: "catering manager"
            },
            {
                label: "Catering Staff",
                value: "catering staff"
            },
            {
                label: "Caulker",
                value: "caulker"
            },
            {
                label: "Ceiling Contractor",
                value: "ceiling contractor"
            },
            {
                label: "Ceiling Fitter",
                value: "ceiling fitter"
            },
            {
                label: "Ceiling Fixer",
                value: "ceiling fixer"
            },
            {
                label: "Cellarman",
                value: "cellarman"
            },
            {
                label: "Centre Lathe Operator",
                value: "centre lathe operator"
            },
            {
                label: "Certified Accountant",
                value: "certified accountant"
            },
            {
                label: "Chambermaid",
                value: "chambermaid"
            },
            {
                label: "Chandler",
                value: "chandler"
            },
            {
                label: "Chaplain",
                value: "chaplain"
            },
            {
                label: "Charge Hand",
                value: "charge hand"
            },
            {
                label: "Charity Worker",
                value: "charity worker"
            },
            {
                label: "Chartered Accountant",
                value: "chartered accountant"
            },
            {
                label: "Chartered Engineer",
                value: "chartered engineer"
            },
            {
                label: "Chartered Surveyor",
                value: "chartered surveyor"
            },
            {
                label: "Chartered Valuer",
                value: "chartered valuer"
            },
            {
                label: "Charterer",
                value: "charterer"
            },
            {
                label: "Chauffeur",
                value: "chauffeur"
            },
            {
                label: "Check-Out Assistant",
                value: "check-out assistant"
            },
            {
                label: "Chef",
                value: "chef"
            },
            {
                label: "Chemical Engineer",
                value: "chemical engineer"
            },
            {
                label: "Chemist",
                value: "chemist"
            },
            {
                label: "Chicken Chaser",
                value: "chicken chaser"
            },
            {
                label: "Chicken Sexer",
                value: "chicken sexer"
            },
            {
                label: "Chief Cashier",
                value: "chief cashier"
            },
            {
                label: "Chief Chemist",
                value: "chief chemist"
            },
            {
                label: "Chief Executive",
                value: "chief executive"
            },
            {
                label: "Child Care Officer (Local Government)",
                value: "child care officer (local government)"
            },
            {
                label: "Child Minder",
                value: "child minder"
            },
            {
                label: "Childminder",
                value: "childminder"
            },
            {
                label: "Children's Entertainer",
                value: "children's entertainer"
            },
            {
                label: "Chimney Sweep",
                value: "chimney sweep"
            },
            {
                label: "China Restorer",
                value: "china restorer"
            },
            {
                label: "Chiropodist",
                value: "chiropodist"
            },
            {
                label: "Chiropractor",
                value: "chiropractor"
            },
            {
                label: "Choirmaster",
                value: "choirmaster"
            },
            {
                label: "Choreographer",
                value: "choreographer"
            },
            {
                label: "Chorister",
                value: "chorister"
            },
            {
                label: "Church Army Worker",
                value: "church army worker"
            },
            {
                label: "Church Dean",
                value: "church dean"
            },
            {
                label: "Church Officer",
                value: "church officer"
            },
            {
                label: "Church Warden",
                value: "church warden"
            },
            {
                label: "Cinema Assistant",
                value: "cinema assistant"
            },
            {
                label: "Cinema Manager",
                value: "cinema manager"
            },
            {
                label: "Circus Proprietor",
                value: "circus proprietor"
            },
            {
                label: "Circus Worker",
                value: "circus worker"
            },
            {
                label: "Civil Engineer",
                value: "civil engineer"
            },
            {
                label: "Civil Servant",
                value: "civil servant"
            },
            {
                label: "Claims Adjustor",
                value: "claims adjustor"
            },
            {
                label: "Claims Assessor",
                value: "claims assessor"
            },
            {
                label: "Claims Manager",
                value: "claims manager"
            },
            {
                label: "Claims / Loss Adjustor",
                value: "claims / loss adjustor"
            },
            {
                label: "Clairvoyant",
                value: "clairvoyant"
            },
            {
                label: "Clapper",
                value: "clapper"
            },
            {
                label: "Classical Musician",
                value: "classical musician"
            },
            {
                label: "Classroom Aide",
                value: "classroom aide"
            },
            {
                label: "Clay Pigeon Instructor",
                value: "clay pigeon instructor"
            },
            {
                label: "Cleaner",
                value: "cleaner"
            },
            {
                label: "Cleaning Contractor",
                value: "cleaning contractor"
            },
            {
                label: "Cleaning Supervisor",
                value: "cleaning supervisor"
            },
            {
                label: "Clergyman",
                value: "clergyman"
            },
            {
                label: "Cleric",
                value: "cleric"
            },
            {
                label: "Clerical Assistant",
                value: "clerical assistant"
            },
            {
                label: "Clerical Officer",
                value: "clerical officer"
            },
            {
                label: "Clerk",
                value: "clerk"
            },
            {
                label: "Clerk of Court",
                value: "clerk of court"
            },
            {
                label: "Clerk Of Works",
                value: "clerk of works"
            },
            {
                label: "Clinical Psychologist",
                value: "clinical psychologist"
            },
            {
                label: "Clock Maker",
                value: "clock maker"
            },
            {
                label: "Clothing Design Cutter",
                value: "clothing design cutter"
            },
            {
                label: "Coach",
                value: "coach"
            },
            {
                label: "Coach Builder",
                value: "coach builder"
            },
            {
                label: "Coach Driver",
                value: "coach driver"
            },
            {
                label: "Coach Sprayer",
                value: "coach sprayer"
            },
            {
                label: "Coal Merchant",
                value: "coal merchant"
            },
            {
                label: "Coalman",
                value: "coalman"
            },
            {
                label: "Coastguard",
                value: "coastguard"
            },
            {
                label: "Cobbler",
                value: "cobbler"
            },
            {
                label: "Coffee Merchant",
                value: "coffee merchant"
            },
            {
                label: "Coin Dealer",
                value: "coin dealer"
            },
            {
                label: "College Dean",
                value: "college dean"
            },
            {
                label: "College Lecturer",
                value: "college lecturer"
            },
            {
                label: "College Principal",
                value: "college principal"
            },
            {
                label: "College Scout",
                value: "college scout"
            },
            {
                label: "Colourist (Maps / Photographs)",
                value: "colourist (maps / photographs)"
            },
            {
                label: "Comedian",
                value: "comedian"
            },
            {
                label: "Commercial Artist",
                value: "commercial artist"
            },
            {
                label: "Commercial Manager",
                value: "commercial manager"
            },
            {
                label: "Commercial Traveller",
                value: "commercial traveller"
            },
            {
                label: "Commission Agent",
                value: "commission agent"
            },
            {
                label: "Commissionaire",
                value: "commissionaire"
            },
            {
                label: "Commissioned Officer",
                value: "commissioned officer"
            },
            {
                label: "Commissioner of Police",
                value: "commissioner of police"
            },
            {
                label: "Commissioning Engineer",
                value: "commissioning engineer"
            },
            {
                label: "Commodity Broker",
                value: "commodity broker"
            },
            {
                label: "Commodity Dealer",
                value: "commodity dealer"
            },
            {
                label: "Communications Officer",
                value: "communications officer"
            },
            {
                label: "Communications Supervisor",
                value: "communications supervisor"
            },
            {
                label: "Community Craft Instructor",
                value: "community craft instructor"
            },
            {
                label: "Community Nurse",
                value: "community nurse"
            },
            {
                label: "Community Worker",
                value: "community worker"
            },
            {
                label: "Companion",
                value: "companion"
            },
            {
                label: "Companion Nurse",
                value: "companion nurse"
            },
            {
                label: "Company Chairman",
                value: "company chairman"
            },
            {
                label: "Company Director",
                value: "company director"
            },
            {
                label: "Company Search Agent",
                value: "company search agent"
            },
            {
                label: "Company Secretary",
                value: "company secretary"
            },
            {
                label: "Compiler",
                value: "compiler"
            },
            {
                label: "Complementary Therapist",
                value: "complementary therapist"
            },
            {
                label: "Compliance Officer",
                value: "compliance officer"
            },
            {
                label: "Composer",
                value: "composer"
            },
            {
                label: "Compositor",
                value: "compositor"
            },
            {
                label: "Computer Analyst",
                value: "computer analyst"
            },
            {
                label: "Computer Consultant",
                value: "computer consultant"
            },
            {
                label: "Computer Editor",
                value: "computer editor"
            },
            {
                label: "Computer Engineer",
                value: "computer engineer"
            },
            {
                label: "Computer Manager",
                value: "computer manager"
            },
            {
                label: "Computer Network Controller",
                value: "computer network controller"
            },
            {
                label: "Computer Operator",
                value: "computer operator"
            },
            {
                label: "Computer Programmer",
                value: "computer programmer"
            },
            {
                label: "Computer Technician",
                value: "computer technician"
            },
            {
                label: "Computing",
                value: "computing"
            },
            {
                label: "Conductor",
                value: "conductor"
            },
            {
                label: "Confectioner",
                value: "confectioner"
            },
            {
                label: "Conference Manager",
                value: "conference manager"
            },
            {
                label: "Conference Organiser",
                value: "conference organiser"
            },
            {
                label: "Conservationist",
                value: "conservationist"
            },
            {
                label: "Conservator",
                value: "conservator"
            },
            {
                label: "Construction Engineer",
                value: "construction engineer"
            },
            {
                label: "Construction Worker",
                value: "construction worker"
            },
            {
                label: "Consultant",
                value: "consultant"
            },
            {
                label: "Consultant Engineer",
                value: "consultant engineer"
            },
            {
                label: "Consumer Scientist",
                value: "consumer scientist"
            },
            {
                label: "Contract Cleaner",
                value: "contract cleaner"
            },
            {
                label: "Contract Furnisher",
                value: "contract furnisher"
            },
            {
                label: "Contract Manager",
                value: "contract manager"
            },
            {
                label: "Contractor",
                value: "contractor"
            },
            {
                label: "Contracts Supervisor",
                value: "contracts supervisor"
            },
            {
                label: "Conveyancer",
                value: "conveyancer"
            },
            {
                label: "Cook",
                value: "cook"
            },
            {
                label: "Cooper",
                value: "cooper"
            },
            {
                label: "Copier",
                value: "copier"
            },
            {
                label: "Coppersmith",
                value: "coppersmith"
            },
            {
                label: "Copy Typist",
                value: "copy typist"
            },
            {
                label: "Copywriter",
                value: "copywriter"
            },
            {
                label: "Coroner",
                value: "coroner"
            },
            {
                label: "Correspondent Press",
                value: "correspondent press"
            },
            {
                label: "Corrosion Consultant",
                value: "corrosion consultant"
            },
            {
                label: "Costume Designer",
                value: "costume designer"
            },
            {
                label: "Costume Jeweller",
                value: "costume jeweller"
            },
            {
                label: "Costumier",
                value: "costumier"
            },
            {
                label: "Council Worker",
                value: "council worker"
            },
            {
                label: "Counsellor",
                value: "counsellor"
            },
            {
                label: "Counter Staff",
                value: "counter staff"
            },
            {
                label: "Countryside Ranger",
                value: "countryside ranger"
            },
            {
                label: "County Councillor",
                value: "county councillor"
            },
            {
                label: "Courier",
                value: "courier"
            },
            {
                label: "Courier - Driver",
                value: "courier - driver"
            },
            {
                label: "Courier - Motorcycle",
                value: "courier - motorcycle"
            },
            {
                label: "Courier - Parcel Delivery",
                value: "courier - parcel delivery"
            },
            {
                label: "Court Officer",
                value: "court officer"
            },
            {
                label: "Court Reporter",
                value: "court reporter"
            },
            {
                label: "Coxswain",
                value: "coxswain"
            },
            {
                label: "Craft Dealer",
                value: "craft dealer"
            },
            {
                label: "Craftsman",
                value: "craftsman"
            },
            {
                label: "Craftswoman",
                value: "craftswoman"
            },
            {
                label: "Crane Driver",
                value: "crane driver"
            },
            {
                label: "Crane Erector",
                value: "crane erector"
            },
            {
                label: "Crane Operator",
                value: "crane operator"
            },
            {
                label: "Crash Assessor",
                value: "crash assessor"
            },
            {
                label: "Creative Director",
                value: "creative director"
            },
            {
                label: "Creche Worker",
                value: "creche worker"
            },
            {
                label: "Credit Broker",
                value: "credit broker"
            },
            {
                label: "Credit Control",
                value: "credit control"
            },
            {
                label: "Credit Controller",
                value: "credit controller"
            },
            {
                label: "Credit Draper",
                value: "credit draper"
            },
            {
                label: "Credit Manager",
                value: "credit manager"
            },
            {
                label: "Credit Researcher",
                value: "credit researcher"
            },
            {
                label: "Crematorium Attendant",
                value: "crematorium attendant"
            },
            {
                label: "Cricketer",
                value: "cricketer"
            },
            {
                label: "Crime Examiner",
                value: "crime examiner"
            },
            {
                label: "Criminologist",
                value: "criminologist"
            },
            {
                label: "Crofter",
                value: "crofter"
            },
            {
                label: "Croupier",
                value: "croupier"
            },
            {
                label: "Crown Prosecutor",
                value: "crown prosecutor"
            },
            {
                label: "Curate",
                value: "curate"
            },
            {
                label: "Curator",
                value: "curator"
            },
            {
                label: "Currency Trader",
                value: "currency trader"
            },
            {
                label: "Curtain Hanger",
                value: "curtain hanger"
            },
            {
                label: "Curtain Maker",
                value: "curtain maker"
            },
            {
                label: "Customer Advisor",
                value: "customer advisor"
            },
            {
                label: "Customer Liaison Officer",
                value: "customer liaison officer"
            },
            {
                label: "Customer Service Controller",
                value: "customer service controller"
            },
            {
                label: "Customs & Excise Officer",
                value: "customs & excise officer"
            },
            {
                label: "Customs And Excise",
                value: "customs and excise"
            },
            {
                label: "Cutler",
                value: "cutler"
            },
            {
                label: "Cutter",
                value: "cutter"
            },
            {
                label: "Cycle Repairer",
                value: "cycle repairer"
            },
            {
                label: "Cyclist",
                value: "cyclist"
            },
            {
                label: "Dairy Engineer",
                value: "dairy engineer"
            },
            {
                label: "Dairy Worker",
                value: "dairy worker"
            },
            {
                label: "Dance Teacher",
                value: "dance teacher"
            },
            {
                label: "Dancer",
                value: "dancer"
            },
            {
                label: "Dark Room Technician",
                value: "dark room technician"
            },
            {
                label: "Data Administrator",
                value: "data administrator"
            },
            {
                label: "Data Co-Ordinator",
                value: "data co-ordinator"
            },
            {
                label: "Data Processing Manager",
                value: "data processing manager"
            },
            {
                label: "Data Processor",
                value: "data processor"
            },
            {
                label: "Day Care Officer",
                value: "day care officer"
            },
            {
                label: "Day Centre Officer",
                value: "day centre officer"
            },
            {
                label: "Dealer",
                value: "dealer"
            },
            {
                label: "Dealer - General",
                value: "dealer - general"
            },
            {
                label: "Dealer - Scrap / Waste",
                value: "dealer - scrap / waste"
            },
            {
                label: "Debt Collector",
                value: "debt collector"
            },
            {
                label: "Debt Counsellor",
                value: "debt counsellor"
            },
            {
                label: "Decorator",
                value: "decorator"
            },
            {
                label: "Delivery Courier",
                value: "delivery courier"
            },
            {
                label: "Delivery Driver",
                value: "delivery driver"
            },
            {
                label: "Delivery Roundsman",
                value: "delivery roundsman"
            },
            {
                label: "Demolition Contractor",
                value: "demolition contractor"
            },
            {
                label: "Demolition Worker",
                value: "demolition worker"
            },
            {
                label: "Demonstrator",
                value: "demonstrator"
            },
            {
                label: "Dendrochronologist",
                value: "dendrochronologist"
            },
            {
                label: "Dental Assistant",
                value: "dental assistant"
            },
            {
                label: "Dental Hygienist",
                value: "dental hygienist"
            },
            {
                label: "Dental Mechanic",
                value: "dental mechanic"
            },
            {
                label: "Dental Nurse",
                value: "dental nurse"
            },
            {
                label: "Dental Surgeon",
                value: "dental surgeon"
            },
            {
                label: "Dental Technician",
                value: "dental technician"
            },
            {
                label: "Dental Therapist",
                value: "dental therapist"
            },
            {
                label: "Dentist",
                value: "dentist"
            },
            {
                label: "Dentist / Dentition",
                value: "dentist / dentition"
            },
            {
                label: "Deputy Head Teacher",
                value: "deputy head teacher"
            },
            {
                label: "Deputy Manager",
                value: "deputy manager"
            },
            {
                label: "Deputy Principal",
                value: "deputy principal"
            },
            {
                label: "Dermatologist",
                value: "dermatologist"
            },
            {
                label: "Design Copier",
                value: "design copier"
            },
            {
                label: "Design Director",
                value: "design director"
            },
            {
                label: "Design Engineer",
                value: "design engineer"
            },
            {
                label: "Design Manager",
                value: "design manager"
            },
            {
                label: "Designer",
                value: "designer"
            },
            {
                label: "Despatch Driver",
                value: "despatch driver"
            },
            {
                label: "Despatch Rider",
                value: "despatch rider"
            },
            {
                label: "Despatch Worker",
                value: "despatch worker"
            },
            {
                label: "Development Manager",
                value: "development manager"
            },
            {
                label: "Diamond Dealer",
                value: "diamond dealer"
            },
            {
                label: "Diecaster",
                value: "diecaster"
            },
            {
                label: "Dietician",
                value: "dietician"
            },
            {
                label: "Dinner Lady",
                value: "dinner lady"
            },
            {
                label: "Diplomat",
                value: "diplomat"
            },
            {
                label: "Diplomatic Staff - British",
                value: "diplomatic staff - british"
            },
            {
                label: "Diplomatic Staff - Foreign",
                value: "diplomatic staff - foreign"
            },
            {
                label: "Diplomatic Staff - Republic Of Ireland",
                value: "diplomatic staff - republic of ireland"
            },
            {
                label: "Director - Performing Arts",
                value: "director - performing arts"
            },
            {
                label: "Director of Environment",
                value: "director of environment"
            },
            {
                label: "Director of Housing",
                value: "director of housing"
            },
            {
                label: "Director of Planning",
                value: "director of planning"
            },
            {
                label: "Director / Company Director",
                value: "director / company director"
            },
            {
                label: "Disc Jockey",
                value: "disc jockey"
            },
            {
                label: "Disco Staff",
                value: "disco staff"
            },
            {
                label: "Dispenser (Pharmacy)",
                value: "dispenser (pharmacy)"
            },
            {
                label: "Distillery Worker",
                value: "distillery worker"
            },
            {
                label: "Distribution Manager",
                value: "distribution manager"
            },
            {
                label: "Distributor (Leaflets / Circ.)",
                value: "distributor (leaflets / circ.)"
            },
            {
                label: "District Nurse",
                value: "district nurse"
            },
            {
                label: "District Valuer",
                value: "district valuer"
            },
            {
                label: "Diver",
                value: "diver"
            },
            {
                label: "Dock Pilot",
                value: "dock pilot"
            },
            {
                label: "Docker",
                value: "docker"
            },
            {
                label: "Dockyard Worker",
                value: "dockyard worker"
            },
            {
                label: "Doctor",
                value: "doctor"
            },
            {
                label: "Doctor - Medical",
                value: "doctor - medical"
            },
            {
                label: "Document Controller",
                value: "document controller"
            },
            {
                label: "Dog Beautician",
                value: "dog beautician"
            },
            {
                label: "Dog Breeder",
                value: "dog breeder"
            },
            {
                label: "Dog Groomer",
                value: "dog groomer"
            },
            {
                label: "Dog Handler",
                value: "dog handler"
            },
            {
                label: "Dog Trainer",
                value: "dog trainer"
            },
            {
                label: "Dog Walker",
                value: "dog walker"
            },
            {
                label: "Dog Warden",
                value: "dog warden"
            },
            {
                label: "Doll Maker",
                value: "doll maker"
            },
            {
                label: "Domestic Staff",
                value: "domestic staff"
            },
            {
                label: "Door Fitter",
                value: "door fitter"
            },
            {
                label: "Door To Door Collector",
                value: "door to door collector"
            },
            {
                label: "Doorman",
                value: "doorman"
            },
            {
                label: "Double Glazing Fitter",
                value: "double glazing fitter"
            },
            {
                label: "Double Glazing Salesman",
                value: "double glazing salesman"
            },
            {
                label: "Draper",
                value: "draper"
            },
            {
                label: "Draughtsman",
                value: "draughtsman"
            },
            {
                label: "Draughtswoman",
                value: "draughtswoman"
            },
            {
                label: "Drayman",
                value: "drayman"
            },
            {
                label: "Dredger Master",
                value: "dredger master"
            },
            {
                label: "Dredgerman",
                value: "dredgerman"
            },
            {
                label: "Dresser Theatre / Films",
                value: "dresser theatre / films"
            },
            {
                label: "Dressmaker",
                value: "dressmaker"
            },
            {
                label: "Driller (Civil Engineering)",
                value: "driller (civil engineering)"
            },
            {
                label: "Drilling Technician",
                value: "drilling technician"
            },
            {
                label: "Driver",
                value: "driver"
            },
            {
                label: "Driver - Hot Food Delivery",
                value: "driver - hot food delivery"
            },
            {
                label: "Driver - Light Goods",
                value: "driver - light goods"
            },
            {
                label: "Driver - PSV",
                value: "driver - psv"
            },
            {
                label: "Driving Examiner",
                value: "driving examiner"
            },
            {
                label: "Driving Instructor",
                value: "driving instructor"
            },
            {
                label: "Driving Instructor - Advanced",
                value: "driving instructor - advanced"
            },
            {
                label: "Driving Instructor (HGV)",
                value: "driving instructor (hgv)"
            },
            {
                label: "Drug Addiction Counsellor",
                value: "drug addiction counsellor"
            },
            {
                label: "Dry Cleaner",
                value: "dry cleaner"
            },
            {
                label: "Dryliner",
                value: "dryliner"
            },
            {
                label: "Dustman",
                value: "dustman"
            },
            {
                label: "Dye Polisher",
                value: "dye polisher"
            },
            {
                label: "Dyer",
                value: "dyer"
            },
            {
                label: "Earth Moving Contractor",
                value: "earth moving contractor"
            },
            {
                label: "Ecologist",
                value: "ecologist"
            },
            {
                label: "Economist",
                value: "economist"
            },
            {
                label: "Editor",
                value: "editor"
            },
            {
                label: "Editor - TV / Films",
                value: "editor - tv / films"
            },
            {
                label: "Editorial Consultant",
                value: "editorial consultant"
            },
            {
                label: "Editorial Staff",
                value: "editorial staff"
            },
            {
                label: "Education Advisor",
                value: "education advisor"
            },
            {
                label: "Education Officer",
                value: "education officer"
            },
            {
                label: "Electrical Contractor",
                value: "electrical contractor"
            },
            {
                label: "Electrical Contracts Manager",
                value: "electrical contracts manager"
            },
            {
                label: "Electrical Engineer",
                value: "electrical engineer"
            },
            {
                label: "Electrical Fitter",
                value: "electrical fitter"
            },
            {
                label: "Electrical Process Worker",
                value: "electrical process worker"
            },
            {
                label: "Electrician",
                value: "electrician"
            },
            {
                label: "Electrician - Vehicle",
                value: "electrician - vehicle"
            },
            {
                label: "Electrologist",
                value: "electrologist"
            },
            {
                label: "Electronic Engineer",
                value: "electronic engineer"
            },
            {
                label: "Electronics Supervisor",
                value: "electronics supervisor"
            },
            {
                label: "Electronics Technician",
                value: "electronics technician"
            },
            {
                label: "Electroplater",
                value: "electroplater"
            },
            {
                label: "Electrotyper",
                value: "electrotyper"
            },
            {
                label: "Embalmer",
                value: "embalmer"
            },
            {
                label: "Embassy Staff",
                value: "embassy staff"
            },
            {
                label: "Embassy Staff - British",
                value: "embassy staff - british"
            },
            {
                label: "Embassy Staff - Foreign",
                value: "embassy staff - foreign"
            },
            {
                label: "Embassy Staff - Republic Of Ireland",
                value: "embassy staff - republic of ireland"
            },
            {
                label: "Embroiderer",
                value: "embroiderer"
            },
            {
                label: "Embryologist",
                value: "embryologist"
            },
            {
                label: "Emergency Service Staff",
                value: "emergency service staff"
            },
            {
                label: "Endocrinologist",
                value: "endocrinologist"
            },
            {
                label: "Energy Analyst",
                value: "energy analyst"
            },
            {
                label: "Engine Room Man",
                value: "engine room man"
            },
            {
                label: "Engineer",
                value: "engineer"
            },
            {
                label: "Engineer Stoker",
                value: "engineer stoker"
            },
            {
                label: "Engineman",
                value: "engineman"
            },
            {
                label: "Engraver",
                value: "engraver"
            },
            {
                label: "Enquiry Agent",
                value: "enquiry agent"
            },
            {
                label: "Entertainer",
                value: "entertainer"
            },
            {
                label: "Entomologist",
                value: "entomologist"
            },
            {
                label: "Environmental Chemist",
                value: "environmental chemist"
            },
            {
                label: "Environmental Consultant",
                value: "environmental consultant"
            },
            {
                label: "Environmental Health Officer",
                value: "environmental health officer"
            },
            {
                label: "Equity Agent",
                value: "equity agent"
            },
            {
                label: "Erector",
                value: "erector"
            },
            {
                label: "Ergonomist",
                value: "ergonomist"
            },
            {
                label: "Escort",
                value: "escort"
            },
            {
                label: "Estate Agent",
                value: "estate agent"
            },
            {
                label: "Estate Manager",
                value: "estate manager"
            },
            {
                label: "Estimator",
                value: "estimator"
            },
            {
                label: "Evangelist",
                value: "evangelist"
            },
            {
                label: "Events Organiser",
                value: "events organiser"
            },
            {
                label: "Examiner",
                value: "examiner"
            },
            {
                label: "Excursion Manager",
                value: "excursion manager"
            },
            {
                label: "Executive",
                value: "executive"
            },
            {
                label: "Executive Officer",
                value: "executive officer"
            },
            {
                label: "Exhaust Fitter",
                value: "exhaust fitter"
            },
            {
                label: "Exhibition Designer",
                value: "exhibition designer"
            },
            {
                label: "Exhibition Organiser",
                value: "exhibition organiser"
            },
            {
                label: "Exotic Dancer",
                value: "exotic dancer"
            },
            {
                label: "Expedition Leader",
                value: "expedition leader"
            },
            {
                label: "Explosives Worker",
                value: "explosives worker"
            },
            {
                label: "Export Consultant",
                value: "export consultant"
            },
            {
                label: "Exporter",
                value: "exporter"
            },
            {
                label: "Extra",
                value: "extra"
            },
            {
                label: "Extrusion Operator",
                value: "extrusion operator"
            },
            {
                label: "Fabricator",
                value: "fabricator"
            },
            {
                label: "Factory Canteen Manager",
                value: "factory canteen manager"
            },
            {
                label: "Factory Inspector",
                value: "factory inspector"
            },
            {
                label: "Factory Manager",
                value: "factory manager"
            },
            {
                label: "Factory Worker",
                value: "factory worker"
            },
            {
                label: "Fairground Worker",
                value: "fairground worker"
            },
            {
                label: "Falconer",
                value: "falconer"
            },
            {
                label: "Farm Manager",
                value: "farm manager"
            },
            {
                label: "Farm Worker",
                value: "farm worker"
            },
            {
                label: "Farmer",
                value: "farmer"
            },
            {
                label: "Farrier",
                value: "farrier"
            },
            {
                label: "Fashion Designer",
                value: "fashion designer"
            },
            {
                label: "Fashion Photographer",
                value: "fashion photographer"
            },
            {
                label: "Fast Food Caterer",
                value: "fast food caterer"
            },
            {
                label: "Fast Food Delivery Driver",
                value: "fast food delivery driver"
            },
            {
                label: "Fast Food Proprietor",
                value: "fast food proprietor"
            },
            {
                label: "Fence Erector",
                value: "fence erector"
            },
            {
                label: "Fettler (Foundry)",
                value: "fettler (foundry)"
            },
            {
                label: "Fibre Glass Moulder",
                value: "fibre glass moulder"
            },
            {
                label: "Field Officer",
                value: "field officer"
            },
            {
                label: "Figure Painter",
                value: "figure painter"
            },
            {
                label: "Filing Clerk",
                value: "filing clerk"
            },
            {
                label: "Film Director",
                value: "film director"
            },
            {
                label: "Film Gaffer",
                value: "film gaffer"
            },
            {
                label: "Film Producer",
                value: "film producer"
            },
            {
                label: "Film Technician",
                value: "film technician"
            },
            {
                label: "Finance Director",
                value: "finance director"
            },
            {
                label: "Finance Manager",
                value: "finance manager"
            },
            {
                label: "Finance Officer",
                value: "finance officer"
            },
            {
                label: "Financial Advisor",
                value: "financial advisor"
            },
            {
                label: "Financial Analyst",
                value: "financial analyst"
            },
            {
                label: "Financial Consultant",
                value: "financial consultant"
            },
            {
                label: "Financier",
                value: "financier"
            },
            {
                label: "Finisher",
                value: "finisher"
            },
            {
                label: "Fire Officer",
                value: "fire officer"
            },
            {
                label: "Fire Prevention Officer",
                value: "fire prevention officer"
            },
            {
                label: "Fire Protection Consultant",
                value: "fire protection consultant"
            },
            {
                label: "Firearms Dealer",
                value: "firearms dealer"
            },
            {
                label: "Firefighter",
                value: "firefighter"
            },
            {
                label: "Fireman / Woman",
                value: "fireman / woman"
            },
            {
                label: "Fireplace Fitter",
                value: "fireplace fitter"
            },
            {
                label: "Firewood Merchant",
                value: "firewood merchant"
            },
            {
                label: "First Aid Instructor",
                value: "first aid instructor"
            },
            {
                label: "First Aid Worker",
                value: "first aid worker"
            },
            {
                label: "Fish Buyer",
                value: "fish buyer"
            },
            {
                label: "Fish Filleter",
                value: "fish filleter"
            },
            {
                label: "Fish Fryer",
                value: "fish fryer"
            },
            {
                label: "Fish Merchant",
                value: "fish merchant"
            },
            {
                label: "Fish Worker",
                value: "fish worker"
            },
            {
                label: "Fisheries Inspector",
                value: "fisheries inspector"
            },
            {
                label: "Fisherman",
                value: "fisherman"
            },
            {
                label: "Fishery Manager",
                value: "fishery manager"
            },
            {
                label: "Fishmonger",
                value: "fishmonger"
            },
            {
                label: "Fitness Instructor",
                value: "fitness instructor"
            },
            {
                label: "Fitter",
                value: "fitter"
            },
            {
                label: "Fitter - Tyre / Exhaust",
                value: "fitter - tyre / exhaust"
            },
            {
                label: "Flagger",
                value: "flagger"
            },
            {
                label: "Fleet Manager",
                value: "fleet manager"
            },
            {
                label: "Flight Deck Crew",
                value: "flight deck crew"
            },
            {
                label: "Flight Dispatcher",
                value: "flight dispatcher"
            },
            {
                label: "Floor Layer",
                value: "floor layer"
            },
            {
                label: "Floor Manager",
                value: "floor manager"
            },
            {
                label: "Florist",
                value: "florist"
            },
            {
                label: "Flour Miller",
                value: "flour miller"
            },
            {
                label: "Flower Arranger",
                value: "flower arranger"
            },
            {
                label: "Flying Instructor",
                value: "flying instructor"
            },
            {
                label: "Foam Convertor",
                value: "foam convertor"
            },
            {
                label: "Food Processor",
                value: "food processor"
            },
            {
                label: "Footballer",
                value: "footballer"
            },
            {
                label: "Footballer - Semi Professional",
                value: "footballer - semi professional"
            },
            {
                label: "Footman",
                value: "footman"
            },
            {
                label: "Forces - Foreign",
                value: "forces - foreign"
            },
            {
                label: "Forces - H.M.",
                value: "forces - h.m."
            },
            {
                label: "Forces - U.S.",
                value: "forces - u.s."
            },
            {
                label: "Foreman",
                value: "foreman"
            },
            {
                label: "Forensic Scientist",
                value: "forensic scientist"
            },
            {
                label: "Forest Ranger",
                value: "forest ranger"
            },
            {
                label: "Forester",
                value: "forester"
            },
            {
                label: "Fork Lift Truck Driver",
                value: "fork lift truck driver"
            },
            {
                label: "Fortune Teller",
                value: "fortune teller"
            },
            {
                label: "Forwarding Agent",
                value: "forwarding agent"
            },
            {
                label: "Foster Parent",
                value: "foster parent"
            },
            {
                label: "Foundry Moulder",
                value: "foundry moulder"
            },
            {
                label: "Foundry Worker",
                value: "foundry worker"
            },
            {
                label: "Fraud Investigator",
                value: "fraud investigator"
            },
            {
                label: "Freelance Photographer",
                value: "freelance photographer"
            },
            {
                label: "French Polisher",
                value: "french polisher"
            },
            {
                label: "Fruiterer",
                value: "fruiterer"
            },
            {
                label: "Fuel Merchant",
                value: "fuel merchant"
            },
            {
                label: "Fund Manager",
                value: "fund manager"
            },
            {
                label: "Fund Raiser",
                value: "fund raiser"
            },
            {
                label: "Funeral Director",
                value: "funeral director"
            },
            {
                label: "Funeral Furnisher",
                value: "funeral furnisher"
            },
            {
                label: "Funfair Employee",
                value: "funfair employee"
            },
            {
                label: "Furnace Man",
                value: "furnace man"
            },
            {
                label: "Furniture Dealer",
                value: "furniture dealer"
            },
            {
                label: "Furniture Remover",
                value: "furniture remover"
            },
            {
                label: "Furniture Restorer",
                value: "furniture restorer"
            },
            {
                label: "Furrier",
                value: "furrier"
            },
            {
                label: "Gallery Owner",
                value: "gallery owner"
            },
            {
                label: "Gambler",
                value: "gambler"
            },
            {
                label: "Gamekeeper",
                value: "gamekeeper"
            },
            {
                label: "Gaming Board Inspector",
                value: "gaming board inspector"
            },
            {
                label: "Gaming Club Manager",
                value: "gaming club manager"
            },
            {
                label: "Gaming Club Proprietor",
                value: "gaming club proprietor"
            },
            {
                label: "Gaming Club Staff - Licensed Premises",
                value: "gaming club staff - licensed premises"
            },
            {
                label: "Gaming Club Staff - Unlicensed Premises",
                value: "gaming club staff - unlicensed premises"
            },
            {
                label: "Garage Attendant",
                value: "garage attendant"
            },
            {
                label: "Garage Foreman",
                value: "garage foreman"
            },
            {
                label: "Garage Manager",
                value: "garage manager"
            },
            {
                label: "Garda",
                value: "garda"
            },
            {
                label: "Garden Designer",
                value: "garden designer"
            },
            {
                label: "Gardener",
                value: "gardener"
            },
            {
                label: "Gas Fitter",
                value: "gas fitter"
            },
            {
                label: "Gas Mechanic",
                value: "gas mechanic"
            },
            {
                label: "Gas Technician",
                value: "gas technician"
            },
            {
                label: "Gate Keeper",
                value: "gate keeper"
            },
            {
                label: "Genealogist",
                value: "genealogist"
            },
            {
                label: "General Manager",
                value: "general manager"
            },
            {
                label: "General Practitioner",
                value: "general practitioner"
            },
            {
                label: "General Worker",
                value: "general worker"
            },
            {
                label: "Geneticist",
                value: "geneticist"
            },
            {
                label: "Geographer",
                value: "geographer"
            },
            {
                label: "Geologist",
                value: "geologist"
            },
            {
                label: "Geophysicist",
                value: "geophysicist"
            },
            {
                label: "Geriatrician",
                value: "geriatrician"
            },
            {
                label: "Gilder",
                value: "gilder"
            },
            {
                label: "Glass Worker",
                value: "glass worker"
            },
            {
                label: "Glazier",
                value: "glazier"
            },
            {
                label: "Goldsmith",
                value: "goldsmith"
            },
            {
                label: "Golf Caddy",
                value: "golf caddy"
            },
            {
                label: "Golf Club Professional",
                value: "golf club professional"
            },
            {
                label: "Golf Coach",
                value: "golf coach"
            },
            {
                label: "Golfer",
                value: "golfer"
            },
            {
                label: "Goods Despatcher",
                value: "goods despatcher"
            },
            {
                label: "Goods Handler",
                value: "goods handler"
            },
            {
                label: "Governor",
                value: "governor"
            },
            {
                label: "Granite Technician",
                value: "granite technician"
            },
            {
                label: "Graphic Designer",
                value: "graphic designer"
            },
            {
                label: "Graphologist",
                value: "graphologist"
            },
            {
                label: "Grave Digger",
                value: "grave digger"
            },
            {
                label: "Gravel Merchant",
                value: "gravel merchant"
            },
            {
                label: "Green Keeper",
                value: "green keeper"
            },
            {
                label: "Greengrocer",
                value: "greengrocer"
            },
            {
                label: "Grocer",
                value: "grocer"
            },
            {
                label: "Groom",
                value: "groom"
            },
            {
                label: "Ground Worker",
                value: "ground worker"
            },
            {
                label: "Groundsman",
                value: "groundsman"
            },
            {
                label: "Guard",
                value: "guard"
            },
            {
                label: "Guest House Owner - Licensed",
                value: "guest house owner - licensed"
            },
            {
                label: "Guest House Owner - Unlicensed",
                value: "guest house owner - unlicensed"
            },
            {
                label: "Guest House Proprietor",
                value: "guest house proprietor"
            },
            {
                label: "Guide",
                value: "guide"
            },
            {
                label: "Gun Smith",
                value: "gun smith"
            },
            {
                label: "Gynaecologist",
                value: "gynaecologist"
            },
            {
                label: "Haematologist",
                value: "haematologist"
            },
            {
                label: "Hair Stylist",
                value: "hair stylist"
            },
            {
                label: "Hairdresser",
                value: "hairdresser"
            },
            {
                label: "Hairdresser - Mobile",
                value: "hairdresser - mobile"
            },
            {
                label: "Handyman",
                value: "handyman"
            },
            {
                label: "Harbour Master",
                value: "harbour master"
            },
            {
                label: "Hardware Dealer",
                value: "hardware dealer"
            },
            {
                label: "Haulage Contractor",
                value: "haulage contractor"
            },
            {
                label: "Hawker",
                value: "hawker"
            },
            {
                label: "Head Accurist",
                value: "head accurist"
            },
            {
                label: "Head Greenkeeper",
                value: "head greenkeeper"
            },
            {
                label: "Head Lad",
                value: "head lad"
            },
            {
                label: "Head of Arts",
                value: "head of arts"
            },
            {
                label: "Head of Trade",
                value: "head of trade"
            },
            {
                label: "Head of Traffic",
                value: "head of traffic"
            },
            {
                label: "Headteacher",
                value: "headteacher"
            },
            {
                label: "Health Advisor",
                value: "health advisor"
            },
            {
                label: "Health And Safety Consultant",
                value: "health and safety consultant"
            },
            {
                label: "Health And Safety Officer",
                value: "health and safety officer"
            },
            {
                label: "Health Care Assistant",
                value: "health care assistant"
            },
            {
                label: "Health Planner",
                value: "health planner"
            },
            {
                label: "Health Service Employee",
                value: "health service employee"
            },
            {
                label: "Health Therapist",
                value: "health therapist"
            },
            {
                label: "Health Visitor",
                value: "health visitor"
            },
            {
                label: "Hearing Aid Technician",
                value: "hearing aid technician"
            },
            {
                label: "Hearing Therapist",
                value: "hearing therapist"
            },
            {
                label: "Heating & Ventilation Engineer",
                value: "heating & ventilation engineer"
            },
            {
                label: "Heating Engineer",
                value: "heating engineer"
            },
            {
                label: "Heating / Ventilation Engineer",
                value: "heating / ventilation engineer"
            },
            {
                label: "Hedger / Ditcher",
                value: "hedger / ditcher"
            },
            {
                label: "Helmsman",
                value: "helmsman"
            },
            {
                label: "Herbalist",
                value: "herbalist"
            },
            {
                label: "Herdsman",
                value: "herdsman"
            },
            {
                label: "HGV Driver - Long haul and/or overnight stays",
                value: "hgv driver - long haul and/or overnight stays"
            },
            {
                label: "HGV Driver - short haul only, no overnight stays",
                value: "hgv driver - short haul only, no overnight stays"
            },
            {
                label: "HGV Mechanic",
                value: "hgv mechanic"
            },
            {
                label: "Highway Inspector",
                value: "highway inspector"
            },
            {
                label: "Hire Car Driver",
                value: "hire car driver"
            },
            {
                label: "Hirer",
                value: "hirer"
            },
            {
                label: "Histologist",
                value: "histologist"
            },
            {
                label: "Historian",
                value: "historian"
            },
            {
                label: "Hod Carrier",
                value: "hod carrier"
            },
            {
                label: "Holiday Camp Staff - Licensed Premises",
                value: "holiday camp staff - licensed premises"
            },
            {
                label: "Holiday Camp Staff - Unlicensed Premises",
                value: "holiday camp staff - unlicensed premises"
            },
            {
                label: "Home Economist",
                value: "home economist"
            },
            {
                label: "Home Help",
                value: "home help"
            },
            {
                label: "Homecare Manager",
                value: "homecare manager"
            },
            {
                label: "Homeopath",
                value: "homeopath"
            },
            {
                label: "Homeworker",
                value: "homeworker"
            },
            {
                label: "Hop Merchant",
                value: "hop merchant"
            },
            {
                label: "Horse Breeder",
                value: "horse breeder"
            },
            {
                label: "Horse Dealer",
                value: "horse dealer"
            },
            {
                label: "Horse Dealer (Non Sport)",
                value: "horse dealer (non sport)"
            },
            {
                label: "Horse Dealer (Sport)",
                value: "horse dealer (sport)"
            },
            {
                label: "Horse Riding Instructor",
                value: "horse riding instructor"
            },
            {
                label: "Horse Trader",
                value: "horse trader"
            },
            {
                label: "Horse Trainer",
                value: "horse trainer"
            },
            {
                label: "Horticultural Consultant",
                value: "horticultural consultant"
            },
            {
                label: "Horticulturalist",
                value: "horticulturalist"
            },
            {
                label: "Hosiery Mechanic",
                value: "hosiery mechanic"
            },
            {
                label: "Hosiery Worker",
                value: "hosiery worker"
            },
            {
                label: "Hospital Consultant",
                value: "hospital consultant"
            },
            {
                label: "Hospital Doctor",
                value: "hospital doctor"
            },
            {
                label: "Hospital Manager",
                value: "hospital manager"
            },
            {
                label: "Hospital Orderly",
                value: "hospital orderly"
            },
            {
                label: "Hospital Photographer",
                value: "hospital photographer"
            },
            {
                label: "Hospital Technician",
                value: "hospital technician"
            },
            {
                label: "Hospital Warden",
                value: "hospital warden"
            },
            {
                label: "Hospital Worker",
                value: "hospital worker"
            },
            {
                label: "Hostess",
                value: "hostess"
            },
            {
                label: "Hot Foil Printer",
                value: "hot foil printer"
            },
            {
                label: "Hotel Consultant",
                value: "hotel consultant"
            },
            {
                label: "Hotel Worker",
                value: "hotel worker"
            },
            {
                label: "Hotelier",
                value: "hotelier"
            },
            {
                label: "House Parent",
                value: "house parent"
            },
            {
                label: "House Sitter",
                value: "house sitter"
            },
            {
                label: "Househusband",
                value: "househusband"
            },
            {
                label: "Housekeeper",
                value: "housekeeper"
            },
            {
                label: "Houseman",
                value: "houseman"
            },
            {
                label: "Housewife",
                value: "housewife"
            },
            {
                label: "Housing Assistant",
                value: "housing assistant"
            },
            {
                label: "Housing Officer",
                value: "housing officer"
            },
            {
                label: "Housing Supervisor",
                value: "housing supervisor"
            },
            {
                label: "Human Resources Manager",
                value: "human resources manager"
            },
            {
                label: "Human Resources Staff",
                value: "human resources staff"
            },
            {
                label: "Hunt Master",
                value: "hunt master"
            },
            {
                label: "Huntsman",
                value: "huntsman"
            },
            {
                label: "Hydro Geologist",
                value: "hydro geologist"
            },
            {
                label: "Hygienist",
                value: "hygienist"
            },
            {
                label: "Hypnotherapist",
                value: "hypnotherapist"
            },
            {
                label: "Hypnotist",
                value: "hypnotist"
            },
            {
                label: "Ice Cream Vendor",
                value: "ice cream vendor"
            },
            {
                label: "Illustrator",
                value: "illustrator"
            },
            {
                label: "Immigration Officer",
                value: "immigration officer"
            },
            {
                label: "Immunologist",
                value: "immunologist"
            },
            {
                label: "Import Consultant",
                value: "import consultant"
            },
            {
                label: "Import / Export Dealer",
                value: "import / export dealer"
            },
            {
                label: "Importer",
                value: "importer"
            },
            {
                label: "Independent Means",
                value: "independent means"
            },
            {
                label: "Induction Moulder",
                value: "induction moulder"
            },
            {
                label: "Industrial Chemist",
                value: "industrial chemist"
            },
            {
                label: "Industrial Consultant",
                value: "industrial consultant"
            },
            {
                label: "Industrial Designer",
                value: "industrial designer"
            },
            {
                label: "Injection Moulder",
                value: "injection moulder"
            },
            {
                label: "Inland Revenue Officer",
                value: "inland revenue officer"
            },
            {
                label: "Insolvency Practitioner",
                value: "insolvency practitioner"
            },
            {
                label: "Inspector",
                value: "inspector"
            },
            {
                label: "Inspector - Customs and Excise",
                value: "inspector - customs and excise"
            },
            {
                label: "Inspector - Insurance",
                value: "inspector - insurance"
            },
            {
                label: "Installation Worker",
                value: "installation worker"
            },
            {
                label: "Instructor",
                value: "instructor"
            },
            {
                label: "Instrument Engineer",
                value: "instrument engineer"
            },
            {
                label: "Instrument Maker",
                value: "instrument maker"
            },
            {
                label: "Instrument Supervisor",
                value: "instrument supervisor"
            },
            {
                label: "Instrument Technician",
                value: "instrument technician"
            },
            {
                label: "Insurance Agent",
                value: "insurance agent"
            },
            {
                label: "Insurance Assessor",
                value: "insurance assessor"
            },
            {
                label: "Insurance Broker",
                value: "insurance broker"
            },
            {
                label: "Insurance Collector",
                value: "insurance collector"
            },
            {
                label: "Insurance Consultant",
                value: "insurance consultant"
            },
            {
                label: "Insurance Inspector",
                value: "insurance inspector"
            },
            {
                label: "Insurance Representative",
                value: "insurance representative"
            },
            {
                label: "Insurance Staff",
                value: "insurance staff"
            },
            {
                label: "Interior Decorator",
                value: "interior decorator"
            },
            {
                label: "Interior Designer",
                value: "interior designer"
            },
            {
                label: "Interpreter",
                value: "interpreter"
            },
            {
                label: "Interviewer",
                value: "interviewer"
            },
            {
                label: "Inventor",
                value: "inventor"
            },
            {
                label: "Investigator",
                value: "investigator"
            },
            {
                label: "Investment Advisor",
                value: "investment advisor"
            },
            {
                label: "Investment Banker",
                value: "investment banker"
            },
            {
                label: "Investment Manager",
                value: "investment manager"
            },
            {
                label: "Invigilator",
                value: "invigilator"
            },
            {
                label: "Ironer",
                value: "ironer"
            },
            {
                label: "Ironer Finisher",
                value: "ironer finisher"
            },
            {
                label: "Ironer Presser",
                value: "ironer presser"
            },
            {
                label: "Ironmonger",
                value: "ironmonger"
            },
            {
                label: "IT Consultant",
                value: "it consultant"
            },
            {
                label: "IT Manager",
                value: "it manager"
            },
            {
                label: "IT Trainer",
                value: "it trainer"
            },
            {
                label: "Itinerant - Labourer",
                value: "itinerant - labourer"
            },
            {
                label: "Itinerant - Trader",
                value: "itinerant - trader"
            },
            {
                label: "Itinerant Labourer",
                value: "itinerant labourer"
            },
            {
                label: "Itinerant Trader",
                value: "itinerant trader"
            },
            {
                label: "Janitor",
                value: "janitor"
            },
            {
                label: "Jazz Composer",
                value: "jazz composer"
            },
            {
                label: "Jeweller",
                value: "jeweller"
            },
            {
                label: "Jewellery Consultant",
                value: "jewellery consultant"
            },
            {
                label: "Jockey",
                value: "jockey"
            },
            {
                label: "Joiner",
                value: "joiner"
            },
            {
                label: "Joinery Consultant",
                value: "joinery consultant"
            },
            {
                label: "Jointer",
                value: "jointer"
            },
            {
                label: "Journalist",
                value: "journalist"
            },
            {
                label: "Journalist - Freelance",
                value: "journalist - freelance"
            },
            {
                label: "Journalistic Agent",
                value: "journalistic agent"
            },
            {
                label: "Judge",
                value: "judge"
            },
            {
                label: "Juggler",
                value: "juggler"
            },
            {
                label: "Junk Shop Proprietor",
                value: "junk shop proprietor"
            },
            {
                label: "Justice Of The Peace",
                value: "justice of the peace"
            },
            {
                label: "Keep Fit Instructor",
                value: "keep fit instructor"
            },
            {
                label: "Kennel Hand",
                value: "kennel hand"
            },
            {
                label: "Kennel Maid",
                value: "kennel maid"
            },
            {
                label: "Kennels / Cattery Employee",
                value: "kennels / cattery employee"
            },
            {
                label: "Kennels / Cattery Owner",
                value: "kennels / cattery owner"
            },
            {
                label: "Kiln Setter",
                value: "kiln setter"
            },
            {
                label: "Kilnman (Glass / Ceramics)",
                value: "kilnman (glass / ceramics)"
            },
            {
                label: "Kissagram Person",
                value: "kissagram person"
            },
            {
                label: "Kitchen Worker",
                value: "kitchen worker"
            },
            {
                label: "Knitter",
                value: "knitter"
            },
            {
                label: "Labelling Operator",
                value: "labelling operator"
            },
            {
                label: "Laboratory Analyst",
                value: "laboratory analyst"
            },
            {
                label: "Laboratory Assistant",
                value: "laboratory assistant"
            },
            {
                label: "Laboratory Attendant",
                value: "laboratory attendant"
            },
            {
                label: "Laboratory Manager",
                value: "laboratory manager"
            },
            {
                label: "Laboratory Operative",
                value: "laboratory operative"
            },
            {
                label: "Laboratory Supervisor",
                value: "laboratory supervisor"
            },
            {
                label: "Laboratory Technician",
                value: "laboratory technician"
            },
            {
                label: "Labourer",
                value: "labourer"
            },
            {
                label: "Laminator",
                value: "laminator"
            },
            {
                label: "Lampshade Maker",
                value: "lampshade maker"
            },
            {
                label: "Land Agent",
                value: "land agent"
            },
            {
                label: "Land Surveyor",
                value: "land surveyor"
            },
            {
                label: "Landlady",
                value: "landlady"
            },
            {
                label: "Landlord",
                value: "landlord"
            },
            {
                label: "Landowner",
                value: "landowner"
            },
            {
                label: "Landscape Architect",
                value: "landscape architect"
            },
            {
                label: "Landscape Gardener",
                value: "landscape gardener"
            },
            {
                label: "Landworker",
                value: "landworker"
            },
            {
                label: "Lathe Operator",
                value: "lathe operator"
            },
            {
                label: "Laundry Staff",
                value: "laundry staff"
            },
            {
                label: "Laundry Worker",
                value: "laundry worker"
            },
            {
                label: "Lavatory Attendant",
                value: "lavatory attendant"
            },
            {
                label: "Law Clerk",
                value: "law clerk"
            },
            {
                label: "Lawn Mower Repairer",
                value: "lawn mower repairer"
            },
            {
                label: "Lawyer",
                value: "lawyer"
            },
            {
                label: "Leaflet Distributor",
                value: "leaflet distributor"
            },
            {
                label: "Leather Worker",
                value: "leather worker"
            },
            {
                label: "Lecturer",
                value: "lecturer"
            },
            {
                label: "Ledger Clerk",
                value: "ledger clerk"
            },
            {
                label: "Legal Advisor",
                value: "legal advisor"
            },
            {
                label: "Legal Assistant",
                value: "legal assistant"
            },
            {
                label: "Legal Executive",
                value: "legal executive"
            },
            {
                label: "Legal Secretary",
                value: "legal secretary"
            },
            {
                label: "Leisure Centre Attendant",
                value: "leisure centre attendant"
            },
            {
                label: "Leisure Centre Manager",
                value: "leisure centre manager"
            },
            {
                label: "Lengthman",
                value: "lengthman"
            },
            {
                label: "Lens Grinder & Polisher",
                value: "lens grinder & polisher"
            },
            {
                label: "Letting Agent",
                value: "letting agent"
            },
            {
                label: "Lexicographer",
                value: "lexicographer"
            },
            {
                label: "Liaison Officer",
                value: "liaison officer"
            },
            {
                label: "Librarian",
                value: "librarian"
            },
            {
                label: "Library Manager",
                value: "library manager"
            },
            {
                label: "Licensee",
                value: "licensee"
            },
            {
                label: "Licensing Consultant",
                value: "licensing consultant"
            },
            {
                label: "Life Assurance Salesman",
                value: "life assurance salesman"
            },
            {
                label: "Lifeguard",
                value: "lifeguard"
            },
            {
                label: "Lift Attendant",
                value: "lift attendant"
            },
            {
                label: "Lift Engineer",
                value: "lift engineer"
            },
            {
                label: "Lighterman",
                value: "lighterman"
            },
            {
                label: "Lighthouse Keeper",
                value: "lighthouse keeper"
            },
            {
                label: "Lighting Designer",
                value: "lighting designer"
            },
            {
                label: "Lighting Director",
                value: "lighting director"
            },
            {
                label: "Lighting Technician",
                value: "lighting technician"
            },
            {
                label: "Lime Kiln Attendant",
                value: "lime kiln attendant"
            },
            {
                label: "Line Manager",
                value: "line manager"
            },
            {
                label: "Line Worker",
                value: "line worker"
            },
            {
                label: "Linesman",
                value: "linesman"
            },
            {
                label: "Linguist",
                value: "linguist"
            },
            {
                label: "Linotype Operator",
                value: "linotype operator"
            },
            {
                label: "Literary Agent",
                value: "literary agent"
            },
            {
                label: "Literary Editor",
                value: "literary editor"
            },
            {
                label: "Lithographer",
                value: "lithographer"
            },
            {
                label: "Litigation Manager",
                value: "litigation manager"
            },
            {
                label: "Loader",
                value: "loader"
            },
            {
                label: "Loans Manager",
                value: "loans manager"
            },
            {
                label: "Local Government Officer",
                value: "local government officer"
            },
            {
                label: "Lock Keeper",
                value: "lock keeper"
            },
            {
                label: "Locksmith",
                value: "locksmith"
            },
            {
                label: "Locum Doctor",
                value: "locum doctor"
            },
            {
                label: "Locum Pharmacist",
                value: "locum pharmacist"
            },
            {
                label: "Log Merchant",
                value: "log merchant"
            },
            {
                label: "Lorry Driver - Long haul and/or overnight stays",
                value: "lorry driver - long haul and/or overnight stays"
            },
            {
                label: "Lorry Driver - short haul only, no overnight stays",
                value: "lorry driver - short haul only, no overnight stays"
            },
            {
                label: "Loss Adjustor",
                value: "loss adjustor"
            },
            {
                label: "Loss Assessor",
                value: "loss assessor"
            },
            {
                label: "Lumberjack",
                value: "lumberjack"
            },
            {
                label: "Machine Fitters Mate",
                value: "machine fitters mate"
            },
            {
                label: "Machine Minder",
                value: "machine minder"
            },
            {
                label: "Machine Operator",
                value: "machine operator"
            },
            {
                label: "Machine Setter",
                value: "machine setter"
            },
            {
                label: "Machine Technician",
                value: "machine technician"
            },
            {
                label: "Machine Tool Engineer",
                value: "machine tool engineer"
            },
            {
                label: "Machine Tool Fitter",
                value: "machine tool fitter"
            },
            {
                label: "Machinist",
                value: "machinist"
            },
            {
                label: "Magician",
                value: "magician"
            },
            {
                label: "Magistrate",
                value: "magistrate"
            },
            {
                label: "Magistrates Clerk",
                value: "magistrates clerk"
            },
            {
                label: "Maid",
                value: "maid"
            },
            {
                label: "Mail Order Worker",
                value: "mail order worker"
            },
            {
                label: "Maintenance Engineer",
                value: "maintenance engineer"
            },
            {
                label: "Maintenance Fitter",
                value: "maintenance fitter"
            },
            {
                label: "Maintenance Man",
                value: "maintenance man"
            },
            {
                label: "Maintenance Manager",
                value: "maintenance manager"
            },
            {
                label: "Maintenance Staff",
                value: "maintenance staff"
            },
            {
                label: "Maître D Hotel",
                value: "maître d hotel"
            },
            {
                label: "Make Up Artist",
                value: "make up artist"
            },
            {
                label: "Make Up Supervisor",
                value: "make up supervisor"
            },
            {
                label: "Management Consultant",
                value: "management consultant"
            },
            {
                label: "Management Trainee",
                value: "management trainee"
            },
            {
                label: "Manager",
                value: "manager"
            },
            {
                label: "Manager - Licensed Premises",
                value: "manager - licensed premises"
            },
            {
                label: "Manager - Retail Shop",
                value: "manager - retail shop"
            },
            {
                label: "Manager - Ring Sports",
                value: "manager - ring sports"
            },
            {
                label: "Manager - Sales (Non Travelling)",
                value: "manager - sales (non travelling)"
            },
            {
                label: "Manager - Sales (Travelling)",
                value: "manager - sales (travelling)"
            },
            {
                label: "Manager - Sports",
                value: "manager - sports"
            },
            {
                label: "Manager - Unlicensed Premises",
                value: "manager - unlicensed premises"
            },
            {
                label: "Managing Clerk",
                value: "managing clerk"
            },
            {
                label: "Managing Director",
                value: "managing director"
            },
            {
                label: "Manicurist",
                value: "manicurist"
            },
            {
                label: "Manufacturing Agent",
                value: "manufacturing agent"
            },
            {
                label: "Manufacturing Engineer",
                value: "manufacturing engineer"
            },
            {
                label: "Manufacturing Technician",
                value: "manufacturing technician"
            },
            {
                label: "Map Maker",
                value: "map maker"
            },
            {
                label: "Map Mounter",
                value: "map mounter"
            },
            {
                label: "Marble Finisher",
                value: "marble finisher"
            },
            {
                label: "Marble Mason",
                value: "marble mason"
            },
            {
                label: "Marine Broker",
                value: "marine broker"
            },
            {
                label: "Marine Consultant",
                value: "marine consultant"
            },
            {
                label: "Marine Electrician",
                value: "marine electrician"
            },
            {
                label: "Marine Engineer",
                value: "marine engineer"
            },
            {
                label: "Marine Geologist",
                value: "marine geologist"
            },
            {
                label: "Marine Pilot",
                value: "marine pilot"
            },
            {
                label: "Marine Surveyor",
                value: "marine surveyor"
            },
            {
                label: "Market Gardener",
                value: "market gardener"
            },
            {
                label: "Market Research Assistant",
                value: "market research assistant"
            },
            {
                label: "Market Researcher",
                value: "market researcher"
            },
            {
                label: "Market Trader",
                value: "market trader"
            },
            {
                label: "Marketing - Non Travelling",
                value: "marketing - non travelling"
            },
            {
                label: "Marketing - Travelling",
                value: "marketing - travelling"
            },
            {
                label: "Marketing Agent",
                value: "marketing agent"
            },
            {
                label: "Marketing Assistant",
                value: "marketing assistant"
            },
            {
                label: "Marketing Consultant",
                value: "marketing consultant"
            },
            {
                label: "Marketing Co-ordinator",
                value: "marketing co-ordinator"
            },
            {
                label: "Marketing Director",
                value: "marketing director"
            },
            {
                label: "Marketing Executive",
                value: "marketing executive"
            },
            {
                label: "Marketing Manager",
                value: "marketing manager"
            },
            {
                label: "Marquee Erector",
                value: "marquee erector"
            },
            {
                label: "Massage Therapist",
                value: "massage therapist"
            },
            {
                label: "Masseur",
                value: "masseur"
            },
            {
                label: "Masseuse",
                value: "masseuse"
            },
            {
                label: "Master Mariner",
                value: "master mariner"
            },
            {
                label: "Master of Ceremonies",
                value: "master of ceremonies"
            },
            {
                label: "Master Of Foxhounds",
                value: "master of foxhounds"
            },
            {
                label: "Materials Controller",
                value: "materials controller"
            },
            {
                label: "Materials Manager",
                value: "materials manager"
            },
            {
                label: "Mathematician",
                value: "mathematician"
            },
            {
                label: "Matron",
                value: "matron"
            },
            {
                label: "Mattress Maker",
                value: "mattress maker"
            },
            {
                label: "Mature Student",
                value: "mature student"
            },
            {
                label: "Mature Student - Living At Home",
                value: "mature student - living at home"
            },
            {
                label: "Mature Student - Living Away",
                value: "mature student - living away"
            },
            {
                label: "Meat Inspector",
                value: "meat inspector"
            },
            {
                label: "Meat Wholesaler",
                value: "meat wholesaler"
            },
            {
                label: "Mechanic",
                value: "mechanic"
            },
            {
                label: "Mechanic - Airport",
                value: "mechanic - airport"
            },
            {
                label: "Mechanic - Vehicle",
                value: "mechanic - vehicle"
            },
            {
                label: "Mechanical Designer",
                value: "mechanical designer"
            },
            {
                label: "Mechanical Engineer",
                value: "mechanical engineer"
            },
            {
                label: "Mechanical Technician",
                value: "mechanical technician"
            },
            {
                label: "Medal Dealer",
                value: "medal dealer"
            },
            {
                label: "Media Critic",
                value: "media critic"
            },
            {
                label: "Media Planner",
                value: "media planner"
            },
            {
                label: "Medical Advisor",
                value: "medical advisor"
            },
            {
                label: "Medical Assistant",
                value: "medical assistant"
            },
            {
                label: "Medical Consultant",
                value: "medical consultant"
            },
            {
                label: "Medical Diagnostician",
                value: "medical diagnostician"
            },
            {
                label: "Medical Officer",
                value: "medical officer"
            },
            {
                label: "Medical Physicist",
                value: "medical physicist"
            },
            {
                label: "Medical Practitioner",
                value: "medical practitioner"
            },
            {
                label: "Medical Researcher",
                value: "medical researcher"
            },
            {
                label: "Medical Secretary",
                value: "medical secretary"
            },
            {
                label: "Medical Student",
                value: "medical student"
            },
            {
                label: "Medical Student - Living At Home",
                value: "medical student - living at home"
            },
            {
                label: "Medical Student - Living Away",
                value: "medical student - living away"
            },
            {
                label: "Medical Supplier",
                value: "medical supplier"
            },
            {
                label: "Medical Technician",
                value: "medical technician"
            },
            {
                label: "Member Of Parliament",
                value: "member of parliament"
            },
            {
                label: "Merchandiser",
                value: "merchandiser"
            },
            {
                label: "Merchant",
                value: "merchant"
            },
            {
                label: "Merchant Banker",
                value: "merchant banker"
            },
            {
                label: "Merchant Navy",
                value: "merchant navy"
            },
            {
                label: "Merchant Seaman",
                value: "merchant seaman"
            },
            {
                label: "Messenger",
                value: "messenger"
            },
            {
                label: "Metal Dealer",
                value: "metal dealer"
            },
            {
                label: "Metal Engineer",
                value: "metal engineer"
            },
            {
                label: "Metal Polisher",
                value: "metal polisher"
            },
            {
                label: "Metal Worker",
                value: "metal worker"
            },
            {
                label: "Metallurgist",
                value: "metallurgist"
            },
            {
                label: "Meteorologist",
                value: "meteorologist"
            },
            {
                label: "Meter Reader",
                value: "meter reader"
            },
            {
                label: "Microbiologist",
                value: "microbiologist"
            },
            {
                label: "Microfilm Operator",
                value: "microfilm operator"
            },
            {
                label: "Microscopist",
                value: "microscopist"
            },
            {
                label: "Midwife",
                value: "midwife"
            },
            {
                label: "Milklady",
                value: "milklady"
            },
            {
                label: "Milkman",
                value: "milkman"
            },
            {
                label: "Mill Operator",
                value: "mill operator"
            },
            {
                label: "Mill Worker",
                value: "mill worker"
            },
            {
                label: "Miller",
                value: "miller"
            },
            {
                label: "Milliner",
                value: "milliner"
            },
            {
                label: "Millwright",
                value: "millwright"
            },
            {
                label: "Miner",
                value: "miner"
            },
            {
                label: "Mineralogist",
                value: "mineralogist"
            },
            {
                label: "Minibus Driver",
                value: "minibus driver"
            },
            {
                label: "Minicab Driver",
                value: "minicab driver"
            },
            {
                label: "Mining Consultant",
                value: "mining consultant"
            },
            {
                label: "Mining Engineer",
                value: "mining engineer"
            },
            {
                label: "Minister Of Religion",
                value: "minister of religion"
            },
            {
                label: "Minister Of The Crown",
                value: "minister of the crown"
            },
            {
                label: "Missionary",
                value: "missionary"
            },
            {
                label: "Mobile Caterer",
                value: "mobile caterer"
            },
            {
                label: "Mobile Disc Jockey",
                value: "mobile disc jockey"
            },
            {
                label: "Mobile Disco Owner",
                value: "mobile disco owner"
            },
            {
                label: "Mobile Hairdresser",
                value: "mobile hairdresser"
            },
            {
                label: "Mobile Motor Mechanic",
                value: "mobile motor mechanic"
            },
            {
                label: "Mobile Service Engineer",
                value: "mobile service engineer"
            },
            {
                label: "Model",
                value: "model"
            },
            {
                label: "Model Maker",
                value: "model maker"
            },
            {
                label: "Moderator",
                value: "moderator"
            },
            {
                label: "Money Broker",
                value: "money broker"
            },
            {
                label: "Money Dealer",
                value: "money dealer"
            },
            {
                label: "Moneylender",
                value: "moneylender"
            },
            {
                label: "Monk",
                value: "monk"
            },
            {
                label: "Monumental Sculptor",
                value: "monumental sculptor"
            },
            {
                label: "Mooring Contractor",
                value: "mooring contractor"
            },
            {
                label: "Mortgage Broker",
                value: "mortgage broker"
            },
            {
                label: "Mortgage Consultant",
                value: "mortgage consultant"
            },
            {
                label: "Mortician",
                value: "mortician"
            },
            {
                label: "Motor Dealer",
                value: "motor dealer"
            },
            {
                label: "Motor Engineer",
                value: "motor engineer"
            },
            {
                label: "Motor Fitter",
                value: "motor fitter"
            },
            {
                label: "Motor Mechanic",
                value: "motor mechanic"
            },
            {
                label: "Motor Racing Driver",
                value: "motor racing driver"
            },
            {
                label: "Motor Racing Organiser",
                value: "motor racing organiser"
            },
            {
                label: "Motor Trader",
                value: "motor trader"
            },
            {
                label: "Moulding Process Technician",
                value: "moulding process technician"
            },
            {
                label: "Museum Assistant",
                value: "museum assistant"
            },
            {
                label: "Museum Attendant",
                value: "museum attendant"
            },
            {
                label: "Museum Consultant",
                value: "museum consultant"
            },
            {
                label: "Museum Technician",
                value: "museum technician"
            },
            {
                label: "Music Producer",
                value: "music producer"
            },
            {
                label: "Music Teacher",
                value: "music teacher"
            },
            {
                label: "Music Therapist",
                value: "music therapist"
            },
            {
                label: "Music Wholesaler",
                value: "music wholesaler"
            },
            {
                label: "Musical Arranger",
                value: "musical arranger"
            },
            {
                label: "Musician",
                value: "musician"
            },
            {
                label: "Musician - Amateur",
                value: "musician - amateur"
            },
            {
                label: "Musician - Classical",
                value: "musician - classical"
            },
            {
                label: "Musician - Dance Band",
                value: "musician - dance band"
            },
            {
                label: "Musician - Pop Group",
                value: "musician - pop group"
            },
            {
                label: "Nanny",
                value: "nanny"
            },
            {
                label: "Naturalist",
                value: "naturalist"
            },
            {
                label: "Naturopath",
                value: "naturopath"
            },
            {
                label: "Navigator",
                value: "navigator"
            },
            {
                label: "Navy - NCO / Commissioned Officer",
                value: "navy - nco / commissioned officer"
            },
            {
                label: "Navy - Other Ranks",
                value: "navy - other ranks"
            },
            {
                label: "Negotiator",
                value: "negotiator"
            },
            {
                label: "Neurologist",
                value: "neurologist"
            },
            {
                label: "Newsagent",
                value: "newsagent"
            },
            {
                label: "Newsreader",
                value: "newsreader"
            },
            {
                label: "Night Club Owner",
                value: "night club owner"
            },
            {
                label: "Night Club Staff",
                value: "night club staff"
            },
            {
                label: "Night Porter",
                value: "night porter"
            },
            {
                label: "Night Watchman",
                value: "night watchman"
            },
            {
                label: "Non Commissioned Officer",
                value: "non commissioned officer"
            },
            {
                label: "Non Professional Footballer",
                value: "non professional footballer"
            },
            {
                label: "Non Professional Sports Coach",
                value: "non professional sports coach"
            },
            {
                label: "Not Employed Due to Disability",
                value: "not employed due to disability"
            },
            {
                label: "Not In Employment",
                value: "not in employment"
            },
            {
                label: "Notary Public",
                value: "notary public"
            },
            {
                label: "Nuclear Scientist",
                value: "nuclear scientist"
            },
            {
                label: "Nun",
                value: "nun"
            },
            {
                label: "Nurse",
                value: "nurse"
            },
            {
                label: "Nursemaid",
                value: "nursemaid"
            },
            {
                label: "Nursery Assistant",
                value: "nursery assistant"
            },
            {
                label: "Nursery Nurse",
                value: "nursery nurse"
            },
            {
                label: "Nursery Worker",
                value: "nursery worker"
            },
            {
                label: "Nurseryman",
                value: "nurseryman"
            },
            {
                label: "Nursing Assistant",
                value: "nursing assistant"
            },
            {
                label: "Nursing Auxiliary",
                value: "nursing auxiliary"
            },
            {
                label: "Nursing Manager",
                value: "nursing manager"
            },
            {
                label: "Nursing Officer",
                value: "nursing officer"
            },
            {
                label: "Nursing Sister",
                value: "nursing sister"
            },
            {
                label: "Nutritionist",
                value: "nutritionist"
            },
            {
                label: "Obstetrician",
                value: "obstetrician"
            },
            {
                label: "Occupational Health Consultant",
                value: "occupational health consultant"
            },
            {
                label: "Occupational Health Nurse",
                value: "occupational health nurse"
            },
            {
                label: "Occupational Therapist",
                value: "occupational therapist"
            },
            {
                label: "Oculist",
                value: "oculist"
            },
            {
                label: "Off Shore Surveyor",
                value: "off shore surveyor"
            },
            {
                label: "Office Administrator",
                value: "office administrator"
            },
            {
                label: "Office Manager",
                value: "office manager"
            },
            {
                label: "Office Worker",
                value: "office worker"
            },
            {
                label: "Oil Broker",
                value: "oil broker"
            },
            {
                label: "Oil Rig Crew",
                value: "oil rig crew"
            },
            {
                label: "Opera Singer",
                value: "opera singer"
            },
            {
                label: "Operations Director",
                value: "operations director"
            },
            {
                label: "Operations Engineer",
                value: "operations engineer"
            },
            {
                label: "Operations Manager",
                value: "operations manager"
            },
            {
                label: "Operations Supervisor",
                value: "operations supervisor"
            },
            {
                label: "Ophthalmic Surgeon",
                value: "ophthalmic surgeon"
            },
            {
                label: "Ophthalmic Technician",
                value: "ophthalmic technician"
            },
            {
                label: "Optical Advisor",
                value: "optical advisor"
            },
            {
                label: "Optical Assistant",
                value: "optical assistant"
            },
            {
                label: "Optical Technician",
                value: "optical technician"
            },
            {
                label: "Optician",
                value: "optician"
            },
            {
                label: "Optometrist",
                value: "optometrist"
            },
            {
                label: "Orchestra Leader",
                value: "orchestra leader"
            },
            {
                label: "Orchestral Violinist",
                value: "orchestral violinist"
            },
            {
                label: "Order Clerk",
                value: "order clerk"
            },
            {
                label: "Orderly",
                value: "orderly"
            },
            {
                label: "Organist",
                value: "organist"
            },
            {
                label: "Ornamental Blacksmith",
                value: "ornamental blacksmith"
            },
            {
                label: "Ornithologist",
                value: "ornithologist"
            },
            {
                label: "Orthopaedic Technician",
                value: "orthopaedic technician"
            },
            {
                label: "Orthoptist",
                value: "orthoptist"
            },
            {
                label: "Osteopath",
                value: "osteopath"
            },
            {
                label: "Ostler",
                value: "ostler"
            },
            {
                label: "Other",
                value: "other"
            },
            {
                label: "Outdoor Pursuits Instructor",
                value: "outdoor pursuits instructor"
            },
            {
                label: "Outfitter",
                value: "outfitter"
            },
            {
                label: "Outreach Worker",
                value: "outreach worker"
            },
            {
                label: "Overhead Line Instructor",
                value: "overhead line instructor"
            },
            {
                label: "Overhead Lineman",
                value: "overhead lineman"
            },
            {
                label: "Overlocker",
                value: "overlocker"
            },
            {
                label: "Overseas Mailer",
                value: "overseas mailer"
            },
            {
                label: "Overwriter",
                value: "overwriter"
            },
            {
                label: "Packaging Consultant",
                value: "packaging consultant"
            },
            {
                label: "Packer",
                value: "packer"
            },
            {
                label: "Paediatrician",
                value: "paediatrician"
            },
            {
                label: "Pager Operator",
                value: "pager operator"
            },
            {
                label: "Paint Consultant",
                value: "paint consultant"
            },
            {
                label: "Paint Sprayer",
                value: "paint sprayer"
            },
            {
                label: "Paint Sprayer - Motor Trade",
                value: "paint sprayer - motor trade"
            },
            {
                label: "Paint Sprayer - Non Motor Trade",
                value: "paint sprayer - non motor trade"
            },
            {
                label: "Paint Technologist",
                value: "paint technologist"
            },
            {
                label: "Painter",
                value: "painter"
            },
            {
                label: "Painter And Decorator",
                value: "painter and decorator"
            },
            {
                label: "Palaeobotanist",
                value: "palaeobotanist"
            },
            {
                label: "Palaeontologist",
                value: "palaeontologist"
            },
            {
                label: "Pallet Maker",
                value: "pallet maker"
            },
            {
                label: "Panel Beater",
                value: "panel beater"
            },
            {
                label: "Paper Mill Worker",
                value: "paper mill worker"
            },
            {
                label: "Parachute Packer",
                value: "parachute packer"
            },
            {
                label: "Paramedic",
                value: "paramedic"
            },
            {
                label: "Park Attendant",
                value: "park attendant"
            },
            {
                label: "Park Keeper",
                value: "park keeper"
            },
            {
                label: "Park Ranger",
                value: "park ranger"
            },
            {
                label: "Park / Recreational Attendant",
                value: "park / recreational attendant"
            },
            {
                label: "Partition Erector",
                value: "partition erector"
            },
            {
                label: "Parts Man",
                value: "parts man"
            },
            {
                label: "Parts Manager",
                value: "parts manager"
            },
            {
                label: "Parts Supervisor",
                value: "parts supervisor"
            },
            {
                label: "Party Planner",
                value: "party planner"
            },
            {
                label: "Pasteuriser",
                value: "pasteuriser"
            },
            {
                label: "Pastry Chef",
                value: "pastry chef"
            },
            {
                label: "Patent Agent",
                value: "patent agent"
            },
            {
                label: "Patent Attorney",
                value: "patent attorney"
            },
            {
                label: "Pathologist",
                value: "pathologist"
            },
            {
                label: "Patrol Person",
                value: "patrol person"
            },
            {
                label: "Patrolman",
                value: "patrolman"
            },
            {
                label: "Pattern Cutter",
                value: "pattern cutter"
            },
            {
                label: "Pattern Maker",
                value: "pattern maker"
            },
            {
                label: "Pattern Weaver",
                value: "pattern weaver"
            },
            {
                label: "Paviour",
                value: "paviour"
            },
            {
                label: "Pawnbroker",
                value: "pawnbroker"
            },
            {
                label: "Payment Officer",
                value: "payment officer"
            },
            {
                label: "Payroll Assistant",
                value: "payroll assistant"
            },
            {
                label: "Payroll Clerk",
                value: "payroll clerk"
            },
            {
                label: "Payroll Manager",
                value: "payroll manager"
            },
            {
                label: "Payroll Supervisor",
                value: "payroll supervisor"
            },
            {
                label: "Pearl Stringer",
                value: "pearl stringer"
            },
            {
                label: "Pedicurist",
                value: "pedicurist"
            },
            {
                label: "Pensions Consultant",
                value: "pensions consultant"
            },
            {
                label: "Pensions Manager",
                value: "pensions manager"
            },
            {
                label: "Personal Assistant",
                value: "personal assistant"
            },
            {
                label: "Personnel Administrator",
                value: "personnel administrator"
            },
            {
                label: "Personnel Manager",
                value: "personnel manager"
            },
            {
                label: "Personnel Officer",
                value: "personnel officer"
            },
            {
                label: "Pest Control",
                value: "pest control"
            },
            {
                label: "Pest Controller",
                value: "pest controller"
            },
            {
                label: "Pet Minder",
                value: "pet minder"
            },
            {
                label: "Petrol Station Attendant",
                value: "petrol station attendant"
            },
            {
                label: "Petroleum Engineer",
                value: "petroleum engineer"
            },
            {
                label: "Petty Officer",
                value: "petty officer"
            },
            {
                label: "Pharmaceutical Assistant",
                value: "pharmaceutical assistant"
            },
            {
                label: "Pharmacist",
                value: "pharmacist"
            },
            {
                label: "Pharmacy Manager",
                value: "pharmacy manager"
            },
            {
                label: "Pharmacy Technician",
                value: "pharmacy technician"
            },
            {
                label: "Philatelist",
                value: "philatelist"
            },
            {
                label: "Phlebotomist",
                value: "phlebotomist"
            },
            {
                label: "Photo Engraver",
                value: "photo engraver"
            },
            {
                label: "Photo Laboratory Processor",
                value: "photo laboratory processor"
            },
            {
                label: "Photo Technician",
                value: "photo technician"
            },
            {
                label: "Photocopy Machine Technician",
                value: "photocopy machine technician"
            },
            {
                label: "Photographer",
                value: "photographer"
            },
            {
                label: "Photographer - Fashion",
                value: "photographer - fashion"
            },
            {
                label: "Photographer - Location",
                value: "photographer - location"
            },
            {
                label: "Photographer - Press",
                value: "photographer - press"
            },
            {
                label: "Photographer - Press (Local)",
                value: "photographer - press (local)"
            },
            {
                label: "Photographer - Shop Owner",
                value: "photographer - shop owner"
            },
            {
                label: "Photographer - Street",
                value: "photographer - street"
            },
            {
                label: "Photographer - Studio",
                value: "photographer - studio"
            },
            {
                label: "Photographic Agent",
                value: "photographic agent"
            },
            {
                label: "Physician",
                value: "physician"
            },
            {
                label: "Physicist",
                value: "physicist"
            },
            {
                label: "Physiologist",
                value: "physiologist"
            },
            {
                label: "Physiotherapist",
                value: "physiotherapist"
            },
            {
                label: "Piano Teacher",
                value: "piano teacher"
            },
            {
                label: "Piano Tuner",
                value: "piano tuner"
            },
            {
                label: "Picker",
                value: "picker"
            },
            {
                label: "Picture Editor",
                value: "picture editor"
            },
            {
                label: "Picture Framer",
                value: "picture framer"
            },
            {
                label: "Picture Renovator",
                value: "picture renovator"
            },
            {
                label: "Picture Researcher",
                value: "picture researcher"
            },
            {
                label: "Pig Manager",
                value: "pig manager"
            },
            {
                label: "Pilot",
                value: "pilot"
            },
            {
                label: "Pilot Helicopter",
                value: "pilot helicopter"
            },
            {
                label: "Pilot Test",
                value: "pilot test"
            },
            {
                label: "Pipe Fitter",
                value: "pipe fitter"
            },
            {
                label: "Pipe Inspector",
                value: "pipe inspector"
            },
            {
                label: "Pipe Insulator",
                value: "pipe insulator"
            },
            {
                label: "Pipe Layer",
                value: "pipe layer"
            },
            {
                label: "Planning Engineer",
                value: "planning engineer"
            },
            {
                label: "Planning Manager",
                value: "planning manager"
            },
            {
                label: "Planning Officer",
                value: "planning officer"
            },
            {
                label: "Planning Technician",
                value: "planning technician"
            },
            {
                label: "Plant Attendant",
                value: "plant attendant"
            },
            {
                label: "Plant Breeder",
                value: "plant breeder"
            },
            {
                label: "Plant Driver",
                value: "plant driver"
            },
            {
                label: "Plant Engineer",
                value: "plant engineer"
            },
            {
                label: "Plant Fitter",
                value: "plant fitter"
            },
            {
                label: "Plant Manager",
                value: "plant manager"
            },
            {
                label: "Plant Operator",
                value: "plant operator"
            },
            {
                label: "Plasterer",
                value: "plasterer"
            },
            {
                label: "Plastics Consultant",
                value: "plastics consultant"
            },
            {
                label: "Plastics Engineer",
                value: "plastics engineer"
            },
            {
                label: "Plate Layer",
                value: "plate layer"
            },
            {
                label: "Plater",
                value: "plater"
            },
            {
                label: "Playgroup Assistant",
                value: "playgroup assistant"
            },
            {
                label: "Playgroup Leader",
                value: "playgroup leader"
            },
            {
                label: "Playwright",
                value: "playwright"
            },
            {
                label: "Plumber",
                value: "plumber"
            },
            {
                label: "Plumbing & Heating Engineer",
                value: "plumbing & heating engineer"
            },
            {
                label: "Podiatrist",
                value: "podiatrist"
            },
            {
                label: "Pointsman",
                value: "pointsman"
            },
            {
                label: "Police - Civilian",
                value: "police - civilian"
            },
            {
                label: "Police Community Support Officer",
                value: "police community support officer"
            },
            {
                label: "Police Officer",
                value: "police officer"
            },
            {
                label: "Polisher",
                value: "polisher"
            },
            {
                label: "Pool Attendant",
                value: "pool attendant"
            },
            {
                label: "Pools Collector",
                value: "pools collector"
            },
            {
                label: "Port Officer",
                value: "port officer"
            },
            {
                label: "Porter",
                value: "porter"
            },
            {
                label: "Portfolio Manager",
                value: "portfolio manager"
            },
            {
                label: "Post Card Seller",
                value: "post card seller"
            },
            {
                label: "Post Graduate Student Living at Home",
                value: "post graduate student living at home"
            },
            {
                label: "Post Graduate Student Living Away from Home",
                value: "post graduate student living away from home"
            },
            {
                label: "Post Office Counter Clerk",
                value: "post office counter clerk"
            },
            {
                label: "Post Office Staff",
                value: "post office staff"
            },
            {
                label: "Post Sorter",
                value: "post sorter"
            },
            {
                label: "Post / Telegraph Officer",
                value: "post / telegraph officer"
            },
            {
                label: "Postman",
                value: "postman"
            },
            {
                label: "Postman / Woman",
                value: "postman / woman"
            },
            {
                label: "Postmaster",
                value: "postmaster"
            },
            {
                label: "Postwoman",
                value: "postwoman"
            },
            {
                label: "Potato Merchant",
                value: "potato merchant"
            },
            {
                label: "Potter",
                value: "potter"
            },
            {
                label: "Poultry Worker",
                value: "poultry worker"
            },
            {
                label: "Practice Manager",
                value: "practice manager"
            },
            {
                label: "Preacher",
                value: "preacher"
            },
            {
                label: "Precious Metal Merchant",
                value: "precious metal merchant"
            },
            {
                label: "Precision Engineer",
                value: "precision engineer"
            },
            {
                label: "Premises Security Installers",
                value: "premises security installers"
            },
            {
                label: "Press Correspondent",
                value: "press correspondent"
            },
            {
                label: "Press Officer",
                value: "press officer"
            },
            {
                label: "Press Operator",
                value: "press operator"
            },
            {
                label: "Press Photographer - Freelance",
                value: "press photographer - freelance"
            },
            {
                label: "Press Photographer - National",
                value: "press photographer - national"
            },
            {
                label: "Press Producer",
                value: "press producer"
            },
            {
                label: "Press Setter",
                value: "press setter"
            },
            {
                label: "Presser",
                value: "presser"
            },
            {
                label: "Priest",
                value: "priest"
            },
            {
                label: "Principal",
                value: "principal"
            },
            {
                label: "Print Finisher",
                value: "print finisher"
            },
            {
                label: "Printer",
                value: "printer"
            },
            {
                label: "Printing Press Operator",
                value: "printing press operator"
            },
            {
                label: "Prison Chaplain",
                value: "prison chaplain"
            },
            {
                label: "Prison Officer",
                value: "prison officer"
            },
            {
                label: "Private Detective",
                value: "private detective"
            },
            {
                label: "Private Investigator",
                value: "private investigator"
            },
            {
                label: "Privy Councillor",
                value: "privy councillor"
            },
            {
                label: "Probation Officer",
                value: "probation officer"
            },
            {
                label: "Probation Worker",
                value: "probation worker"
            },
            {
                label: "Process Engineer",
                value: "process engineer"
            },
            {
                label: "Process Operator",
                value: "process operator"
            },
            {
                label: "Process Worker",
                value: "process worker"
            },
            {
                label: "Procurator Fiscal",
                value: "procurator fiscal"
            },
            {
                label: "Produce Supervisor",
                value: "produce supervisor"
            },
            {
                label: "Producer",
                value: "producer"
            },
            {
                label: "Product Designer",
                value: "product designer"
            },
            {
                label: "Product Installer",
                value: "product installer"
            },
            {
                label: "Product Manager",
                value: "product manager"
            },
            {
                label: "Production Engineer",
                value: "production engineer"
            },
            {
                label: "Production Hand",
                value: "production hand"
            },
            {
                label: "Production Manager",
                value: "production manager"
            },
            {
                label: "Production Planner",
                value: "production planner"
            },
            {
                label: "Professional Apprentice Footballer",
                value: "professional apprentice footballer"
            },
            {
                label: "Professional Boxer",
                value: "professional boxer"
            },
            {
                label: "Professional Cricketer",
                value: "professional cricketer"
            },
            {
                label: "Professional Cyclist",
                value: "professional cyclist"
            },
            {
                label: "Professional Footballer",
                value: "professional footballer"
            },
            {
                label: "Professional Racing Driver",
                value: "professional racing driver"
            },
            {
                label: "Professional Racing Motorcyclist",
                value: "professional racing motorcyclist"
            },
            {
                label: "Professional Sports Coach",
                value: "professional sports coach"
            },
            {
                label: "Professional Sportsperson",
                value: "professional sportsperson"
            },
            {
                label: "Professional Wrestler",
                value: "professional wrestler"
            },
            {
                label: "Professor",
                value: "professor"
            },
            {
                label: "Progress Chaser",
                value: "progress chaser"
            },
            {
                label: "Progress Clerk",
                value: "progress clerk"
            },
            {
                label: "Project Co-ordinator",
                value: "project co-ordinator"
            },
            {
                label: "Project Engineer",
                value: "project engineer"
            },
            {
                label: "Project Leader",
                value: "project leader"
            },
            {
                label: "Project Manager",
                value: "project manager"
            },
            {
                label: "Project Worker",
                value: "project worker"
            },
            {
                label: "Projectionist",
                value: "projectionist"
            },
            {
                label: "Promoter",
                value: "promoter"
            },
            {
                label: "Promoter - Entertainments",
                value: "promoter - entertainments"
            },
            {
                label: "Promoter - Racing",
                value: "promoter - racing"
            },
            {
                label: "Promoter - Ring Sports",
                value: "promoter - ring sports"
            },
            {
                label: "Promoter - Sports",
                value: "promoter - sports"
            },
            {
                label: "Proof Reader",
                value: "proof reader"
            },
            {
                label: "Property Buyer",
                value: "property buyer"
            },
            {
                label: "Property Dealer",
                value: "property dealer"
            },
            {
                label: "Property Developer",
                value: "property developer"
            },
            {
                label: "Property Manager",
                value: "property manager"
            },
            {
                label: "Property Valuer",
                value: "property valuer"
            },
            {
                label: "Proprietor",
                value: "proprietor"
            },
            {
                label: "Prosthetist",
                value: "prosthetist"
            },
            {
                label: "Psychiatrist",
                value: "psychiatrist"
            },
            {
                label: "Psychoanalyst",
                value: "psychoanalyst"
            },
            {
                label: "Psychodynamic Counsellor",
                value: "psychodynamic counsellor"
            },
            {
                label: "Psychologist",
                value: "psychologist"
            },
            {
                label: "Psychotherapist",
                value: "psychotherapist"
            },
            {
                label: "Public House Manager",
                value: "public house manager"
            },
            {
                label: "Public Relations Officer",
                value: "public relations officer"
            },
            {
                label: "Publican",
                value: "publican"
            },
            {
                label: "Publicity Manager",
                value: "publicity manager"
            },
            {
                label: "Publisher",
                value: "publisher"
            },
            {
                label: "Publishing Manager",
                value: "publishing manager"
            },
            {
                label: "Purchase Clerk",
                value: "purchase clerk"
            },
            {
                label: "Purchase Ledger Clerk",
                value: "purchase ledger clerk"
            },
            {
                label: "Purchasing Assistant",
                value: "purchasing assistant"
            },
            {
                label: "Purchasing Manager",
                value: "purchasing manager"
            },
            {
                label: "Purser",
                value: "purser"
            },
            {
                label: "Quality Controller",
                value: "quality controller"
            },
            {
                label: "Quality Engineer",
                value: "quality engineer"
            },
            {
                label: "Quality Inspector",
                value: "quality inspector"
            },
            {
                label: "Quality Manager",
                value: "quality manager"
            },
            {
                label: "Quality Technician",
                value: "quality technician"
            },
            {
                label: "Quantity Surveyor",
                value: "quantity surveyor"
            },
            {
                label: "Quarry Worker",
                value: "quarry worker"
            },
            {
                label: "Queens Counsel",
                value: "queens counsel"
            },
            {
                label: "Rabbi",
                value: "rabbi"
            },
            {
                label: "Racehorse Groom",
                value: "racehorse groom"
            },
            {
                label: "Racing Motorcyclist",
                value: "racing motorcyclist"
            },
            {
                label: "Racing Organiser",
                value: "racing organiser"
            },
            {
                label: "Radar Mechanic",
                value: "radar mechanic"
            },
            {
                label: "Radio Controller",
                value: "radio controller"
            },
            {
                label: "Radio Director",
                value: "radio director"
            },
            {
                label: "Radio Engineer",
                value: "radio engineer"
            },
            {
                label: "Radio Helpline Coordinator",
                value: "radio helpline coordinator"
            },
            {
                label: "Radio Mechanic",
                value: "radio mechanic"
            },
            {
                label: "Radio Operator",
                value: "radio operator"
            },
            {
                label: "Radio Presenter",
                value: "radio presenter"
            },
            {
                label: "Radio Producer",
                value: "radio producer"
            },
            {
                label: "Radiographer",
                value: "radiographer"
            },
            {
                label: "Radiologist",
                value: "radiologist"
            },
            {
                label: "Radiotherapist",
                value: "radiotherapist"
            },
            {
                label: "Railman",
                value: "railman"
            },
            {
                label: "Railway Staff",
                value: "railway staff"
            },
            {
                label: "Rally Driver",
                value: "rally driver"
            },
            {
                label: "Ramp Agent",
                value: "ramp agent"
            },
            {
                label: "Reactor Attendant",
                value: "reactor attendant"
            },
            {
                label: "Reader",
                value: "reader"
            },
            {
                label: "Reader Compositor",
                value: "reader compositor"
            },
            {
                label: "Receptionist",
                value: "receptionist"
            },
            {
                label: "Records Supervisor",
                value: "records supervisor"
            },
            {
                label: "Recovery Vehicle Co-ordinator",
                value: "recovery vehicle co-ordinator"
            },
            {
                label: "Recruitment Consultant",
                value: "recruitment consultant"
            },
            {
                label: "Rector",
                value: "rector"
            },
            {
                label: "Referee",
                value: "referee"
            },
            {
                label: "Refit Merchandiser",
                value: "refit merchandiser"
            },
            {
                label: "Reflexologist",
                value: "reflexologist"
            },
            {
                label: "Refractory Engineer",
                value: "refractory engineer"
            },
            {
                label: "Refrigeration Engineer",
                value: "refrigeration engineer"
            },
            {
                label: "Refuse Collector",
                value: "refuse collector"
            },
            {
                label: "Registered Disabled",
                value: "registered disabled"
            },
            {
                label: "Registrar",
                value: "registrar"
            },
            {
                label: "Regulator",
                value: "regulator"
            },
            {
                label: "Relocation Agent",
                value: "relocation agent"
            },
            {
                label: "Remedial Therapist",
                value: "remedial therapist"
            },
            {
                label: "Rent Collector",
                value: "rent collector"
            },
            {
                label: "Rent Officer",
                value: "rent officer"
            },
            {
                label: "Repair Man",
                value: "repair man"
            },
            {
                label: "Reporter",
                value: "reporter"
            },
            {
                label: "Reporter - Freelance",
                value: "reporter - freelance"
            },
            {
                label: "Reprographic Assistant",
                value: "reprographic assistant"
            },
            {
                label: "Rescue Worker",
                value: "rescue worker"
            },
            {
                label: "Research Analyst",
                value: "research analyst"
            },
            {
                label: "Research Assistant",
                value: "research assistant"
            },
            {
                label: "Research Consultant",
                value: "research consultant"
            },
            {
                label: "Research Director",
                value: "research director"
            },
            {
                label: "Research Scientist",
                value: "research scientist"
            },
            {
                label: "Research Technician",
                value: "research technician"
            },
            {
                label: "Researcher",
                value: "researcher"
            },
            {
                label: "Re-Settlement Officer",
                value: "re-settlement officer"
            },
            {
                label: "Resin Caster",
                value: "resin caster"
            },
            {
                label: "Restaurant Manager",
                value: "restaurant manager"
            },
            {
                label: "Restaurateur",
                value: "restaurateur"
            },
            {
                label: "Restorer",
                value: "restorer"
            },
            {
                label: "Retired",
                value: "retired"
            },
            {
                label: "Revenue Clerk",
                value: "revenue clerk"
            },
            {
                label: "Revenue Officer",
                value: "revenue officer"
            },
            {
                label: "Rheumatologist",
                value: "rheumatologist"
            },
            {
                label: "Riding Instructor",
                value: "riding instructor"
            },
            {
                label: "Rig Worker",
                value: "rig worker"
            },
            {
                label: "Rig Worker - Off Shore",
                value: "rig worker - off shore"
            },
            {
                label: "Rigger",
                value: "rigger"
            },
            {
                label: "Riverman",
                value: "riverman"
            },
            {
                label: "Riveter",
                value: "riveter"
            },
            {
                label: "Road Safety Officer",
                value: "road safety officer"
            },
            {
                label: "Road Sweeper",
                value: "road sweeper"
            },
            {
                label: "Road Worker",
                value: "road worker"
            },
            {
                label: "Roof Tiler",
                value: "roof tiler"
            },
            {
                label: "Roofer",
                value: "roofer"
            },
            {
                label: "Rose Grower",
                value: "rose grower"
            },
            {
                label: "Royal Marine",
                value: "royal marine"
            },
            {
                label: "Rubber Worker",
                value: "rubber worker"
            },
            {
                label: "Rug Maker",
                value: "rug maker"
            },
            {
                label: "Rugby Player",
                value: "rugby player"
            },
            {
                label: "Rugby Player - Amateur",
                value: "rugby player - amateur"
            },
            {
                label: "Rugby Player - Professional",
                value: "rugby player - professional"
            },
            {
                label: "Saddler",
                value: "saddler"
            },
            {
                label: "Safety Officer",
                value: "safety officer"
            },
            {
                label: "Sail Maker",
                value: "sail maker"
            },
            {
                label: "Sales - Non Travelling",
                value: "sales - non travelling"
            },
            {
                label: "Sales - Travelling",
                value: "sales - travelling"
            },
            {
                label: "Sales Administrator",
                value: "sales administrator"
            },
            {
                label: "Sales Assistant",
                value: "sales assistant"
            },
            {
                label: "Sales Director",
                value: "sales director"
            },
            {
                label: "Sales Engineer",
                value: "sales engineer"
            },
            {
                label: "Sales Executive",
                value: "sales executive"
            },
            {
                label: "Sales Manager",
                value: "sales manager"
            },
            {
                label: "Sales Representative",
                value: "sales representative"
            },
            {
                label: "Sales Support",
                value: "sales support"
            },
            {
                label: "Sales Woman",
                value: "sales woman"
            },
            {
                label: "Salesman",
                value: "salesman"
            },
            {
                label: "Salvage Dealer",
                value: "salvage dealer"
            },
            {
                label: "Salvage Hand",
                value: "salvage hand"
            },
            {
                label: "Sample Hand",
                value: "sample hand"
            },
            {
                label: "Sand Blaster",
                value: "sand blaster"
            },
            {
                label: "Sand Merchant",
                value: "sand merchant"
            },
            {
                label: "Saw Miller",
                value: "saw miller"
            },
            {
                label: "Sawyer",
                value: "sawyer"
            },
            {
                label: "Scaffolder",
                value: "scaffolder"
            },
            {
                label: "Scenehand",
                value: "scenehand"
            },
            {
                label: "School Counsellor",
                value: "school counsellor"
            },
            {
                label: "School Crossing Warden",
                value: "school crossing warden"
            },
            {
                label: "School Inspector",
                value: "school inspector"
            },
            {
                label: "School Student",
                value: "school student"
            },
            {
                label: "Scientific Officer",
                value: "scientific officer"
            },
            {
                label: "Scientist",
                value: "scientist"
            },
            {
                label: "Scientist - Atomic Energy",
                value: "scientist - atomic energy"
            },
            {
                label: "Scrap Dealer",
                value: "scrap dealer"
            },
            {
                label: "Screen Printer",
                value: "screen printer"
            },
            {
                label: "Screen Writer",
                value: "screen writer"
            },
            {
                label: "Script Writer",
                value: "script writer"
            },
            {
                label: "Sculptor",
                value: "sculptor"
            },
            {
                label: "Seaman",
                value: "seaman"
            },
            {
                label: "Seamstress",
                value: "seamstress"
            },
            {
                label: "Second Hand Dealer",
                value: "second hand dealer"
            },
            {
                label: "Secretary",
                value: "secretary"
            },
            {
                label: "Secretary And PA",
                value: "secretary and pa"
            },
            {
                label: "Security Consultant",
                value: "security consultant"
            },
            {
                label: "Security Controller",
                value: "security controller"
            },
            {
                label: "Security Guard",
                value: "security guard"
            },
            {
                label: "Security Officer",
                value: "security officer"
            },
            {
                label: "Seedsman",
                value: "seedsman"
            },
            {
                label: "Semi-Professional Sportsperson",
                value: "semi-professional sportsperson"
            },
            {
                label: "Servant",
                value: "servant"
            },
            {
                label: "Service Engineer",
                value: "service engineer"
            },
            {
                label: "Service Engineer (Non-Mobile)",
                value: "service engineer (non-mobile)"
            },
            {
                label: "Service Manager",
                value: "service manager"
            },
            {
                label: "Setter",
                value: "setter"
            },
            {
                label: "Sewage Worker",
                value: "sewage worker"
            },
            {
                label: "Share Dealer",
                value: "share dealer"
            },
            {
                label: "Sheet Metal Worker",
                value: "sheet metal worker"
            },
            {
                label: "Shelf Filler",
                value: "shelf filler"
            },
            {
                label: "Shelter Warden",
                value: "shelter warden"
            },
            {
                label: "Shepherd",
                value: "shepherd"
            },
            {
                label: "Sheriff",
                value: "sheriff"
            },
            {
                label: "Sheriff Clerk",
                value: "sheriff clerk"
            },
            {
                label: "Sheriff Principal",
                value: "sheriff principal"
            },
            {
                label: "Sheriffs Officer",
                value: "sheriffs officer"
            },
            {
                label: "Shift Controller",
                value: "shift controller"
            },
            {
                label: "Ship Broker",
                value: "ship broker"
            },
            {
                label: "Ship Builder",
                value: "ship builder"
            },
            {
                label: "Shipping Clerk",
                value: "shipping clerk"
            },
            {
                label: "Shipping Officer",
                value: "shipping officer"
            },
            {
                label: "Shipwright",
                value: "shipwright"
            },
            {
                label: "Shipyard Worker",
                value: "shipyard worker"
            },
            {
                label: "Shoe Maker",
                value: "shoe maker"
            },
            {
                label: "Shoe Repairer",
                value: "shoe repairer"
            },
            {
                label: "Shooting Instructor",
                value: "shooting instructor"
            },
            {
                label: "Shop Assistant",
                value: "shop assistant"
            },
            {
                label: "Shop Fitter",
                value: "shop fitter"
            },
            {
                label: "Shop Keeper",
                value: "shop keeper"
            },
            {
                label: "Shop Manager",
                value: "shop manager"
            },
            {
                label: "Shop Proprietor",
                value: "shop proprietor"
            },
            {
                label: "Shop Proprietor - Mobile",
                value: "shop proprietor - mobile"
            },
            {
                label: "Shorthand Writer",
                value: "shorthand writer"
            },
            {
                label: "Shot Blaster",
                value: "shot blaster"
            },
            {
                label: "Show Jumper",
                value: "show jumper"
            },
            {
                label: "Showman",
                value: "showman"
            },
            {
                label: "Shunter",
                value: "shunter"
            },
            {
                label: "Sign Maker",
                value: "sign maker"
            },
            {
                label: "Signalman",
                value: "signalman"
            },
            {
                label: "Signwriter",
                value: "signwriter"
            },
            {
                label: "Silversmith",
                value: "silversmith"
            },
            {
                label: "Singer",
                value: "singer"
            },
            {
                label: "Site Agent",
                value: "site agent"
            },
            {
                label: "Site Engineer",
                value: "site engineer"
            },
            {
                label: "Skipper",
                value: "skipper"
            },
            {
                label: "Slater",
                value: "slater"
            },
            {
                label: "Slaughterman",
                value: "slaughterman"
            },
            {
                label: "Smallholder",
                value: "smallholder"
            },
            {
                label: "Smelter",
                value: "smelter"
            },
            {
                label: "Snooker Player",
                value: "snooker player"
            },
            {
                label: "Social Worker",
                value: "social worker"
            },
            {
                label: "Sociologist",
                value: "sociologist"
            },
            {
                label: "Software Consultant",
                value: "software consultant"
            },
            {
                label: "Software Engineer",
                value: "software engineer"
            },
            {
                label: "Solar Panel Installer And Repairer",
                value: "solar panel installer and repairer"
            },
            {
                label: "Soldier",
                value: "soldier"
            },
            {
                label: "Solicitor",
                value: "solicitor"
            },
            {
                label: "Song Writer",
                value: "song writer"
            },
            {
                label: "Sorter",
                value: "sorter"
            },
            {
                label: "Sound Editor",
                value: "sound editor"
            },
            {
                label: "Sound Engineer",
                value: "sound engineer"
            },
            {
                label: "Sound Mixer",
                value: "sound mixer"
            },
            {
                label: "Sound Technician",
                value: "sound technician"
            },
            {
                label: "Special Constable",
                value: "special constable"
            },
            {
                label: "Special Needs Assistant",
                value: "special needs assistant"
            },
            {
                label: "Speech Therapist",
                value: "speech therapist"
            },
            {
                label: "Sports Administrator",
                value: "sports administrator"
            },
            {
                label: "Sports Administrator - Other Sports",
                value: "sports administrator - other sports"
            },
            {
                label: "Sports Administrator - Ring Sports",
                value: "sports administrator - ring sports"
            },
            {
                label: "Sports Centre Attendant",
                value: "sports centre attendant"
            },
            {
                label: "Sports Coach",
                value: "sports coach"
            },
            {
                label: "Sports Commentator",
                value: "sports commentator"
            },
            {
                label: "Sports Equipment Repairer",
                value: "sports equipment repairer"
            },
            {
                label: "Sportsman",
                value: "sportsman"
            },
            {
                label: "Sportswoman",
                value: "sportswoman"
            },
            {
                label: "Spring Maker",
                value: "spring maker"
            },
            {
                label: "Stable Hand",
                value: "stable hand"
            },
            {
                label: "Staff Nurse",
                value: "staff nurse"
            },
            {
                label: "Stage Director",
                value: "stage director"
            },
            {
                label: "Stage Hand",
                value: "stage hand"
            },
            {
                label: "Stage Manager",
                value: "stage manager"
            },
            {
                label: "Stage Mover",
                value: "stage mover"
            },
            {
                label: "Stamp Dealer",
                value: "stamp dealer"
            },
            {
                label: "State Enrolled Nurse",
                value: "state enrolled nurse"
            },
            {
                label: "State Registered Nurse",
                value: "state registered nurse"
            },
            {
                label: "Station Manager",
                value: "station manager"
            },
            {
                label: "Stationer",
                value: "stationer"
            },
            {
                label: "Statistician",
                value: "statistician"
            },
            {
                label: "Steel Erector",
                value: "steel erector"
            },
            {
                label: "Steel Worker",
                value: "steel worker"
            },
            {
                label: "Steeplejack",
                value: "steeplejack"
            },
            {
                label: "Stenographer",
                value: "stenographer"
            },
            {
                label: "Stevedore",
                value: "stevedore"
            },
            {
                label: "Steward",
                value: "steward"
            },
            {
                label: "Steward / Stewardess",
                value: "steward / stewardess"
            },
            {
                label: "Stewardess",
                value: "stewardess"
            },
            {
                label: "Stock Controller",
                value: "stock controller"
            },
            {
                label: "Stock Exchange Dealer",
                value: "stock exchange dealer"
            },
            {
                label: "Stock Manager",
                value: "stock manager"
            },
            {
                label: "Stockbroker",
                value: "stockbroker"
            },
            {
                label: "Stockman",
                value: "stockman"
            },
            {
                label: "Stocktaker",
                value: "stocktaker"
            },
            {
                label: "Stone Cutter",
                value: "stone cutter"
            },
            {
                label: "Stone Sawyer",
                value: "stone sawyer"
            },
            {
                label: "Stonemason",
                value: "stonemason"
            },
            {
                label: "Store Detective",
                value: "store detective"
            },
            {
                label: "Store Keeper",
                value: "store keeper"
            },
            {
                label: "Storeman",
                value: "storeman"
            },
            {
                label: "Storeman / Woman",
                value: "storeman / woman"
            },
            {
                label: "Stores Controller",
                value: "stores controller"
            },
            {
                label: "Storewoman",
                value: "storewoman"
            },
            {
                label: "Street Entertainer",
                value: "street entertainer"
            },
            {
                label: "Street Trader",
                value: "street trader"
            },
            {
                label: "Stud Hand",
                value: "stud hand"
            },
            {
                label: "Student",
                value: "student"
            },
            {
                label: "Student - Foreign",
                value: "student - foreign"
            },
            {
                label: "Student - Living at Home",
                value: "student - living at home"
            },
            {
                label: "Student - Living Away",
                value: "student - living away"
            },
            {
                label: "Student Counsellor",
                value: "student counsellor"
            },
            {
                label: "Student Nurse",
                value: "student nurse"
            },
            {
                label: "Student Nurse - Living At Home",
                value: "student nurse - living at home"
            },
            {
                label: "Student Nurse - Living Away",
                value: "student nurse - living away"
            },
            {
                label: "Student Teacher",
                value: "student teacher"
            },
            {
                label: "Student Teacher - Living At Home",
                value: "student teacher - living at home"
            },
            {
                label: "Student Teacher - Living Away",
                value: "student teacher - living away"
            },
            {
                label: "Studio Manager",
                value: "studio manager"
            },
            {
                label: "Sub-Postmaster",
                value: "sub-postmaster"
            },
            {
                label: "Sub-Postmistress",
                value: "sub-postmistress"
            },
            {
                label: "Superintendent",
                value: "superintendent"
            },
            {
                label: "Supervisor",
                value: "supervisor"
            },
            {
                label: "Supply Teacher",
                value: "supply teacher"
            },
            {
                label: "Support Worker",
                value: "support worker"
            },
            {
                label: "Surgeon",
                value: "surgeon"
            },
            {
                label: "Surgeon - N.H.S.",
                value: "surgeon - n.h.s."
            },
            {
                label: "Surgeon - Non N.H.S.",
                value: "surgeon - non n.h.s."
            },
            {
                label: "Surveyor",
                value: "surveyor"
            },
            {
                label: "Surveyor - Chartered",
                value: "surveyor - chartered"
            },
            {
                label: "Systems Analyst",
                value: "systems analyst"
            },
            {
                label: "Systems Engineer",
                value: "systems engineer"
            },
            {
                label: "Systems Manager",
                value: "systems manager"
            },
            {
                label: "Tachograph Analyst",
                value: "tachograph analyst"
            },
            {
                label: "Tacker",
                value: "tacker"
            },
            {
                label: "Tailor",
                value: "tailor"
            },
            {
                label: "Tank Farm Operative",
                value: "tank farm operative"
            },
            {
                label: "Tanker Driver",
                value: "tanker driver"
            },
            {
                label: "Tanner",
                value: "tanner"
            },
            {
                label: "Tape Librarian",
                value: "tape librarian"
            },
            {
                label: "Tape Operator",
                value: "tape operator"
            },
            {
                label: "Tarmacer",
                value: "tarmacer"
            },
            {
                label: "Tarot Reader / Palmistry Expert",
                value: "tarot reader / palmistry expert"
            },
            {
                label: "Taster",
                value: "taster"
            },
            {
                label: "Tattooist",
                value: "tattooist"
            },
            {
                label: "Tax Advisor",
                value: "tax advisor"
            },
            {
                label: "Tax Analyst",
                value: "tax analyst"
            },
            {
                label: "Tax Assistant",
                value: "tax assistant"
            },
            {
                label: "Tax Consultant",
                value: "tax consultant"
            },
            {
                label: "Tax Inspector",
                value: "tax inspector"
            },
            {
                label: "Tax Manager",
                value: "tax manager"
            },
            {
                label: "Tax Officer",
                value: "tax officer"
            },
            {
                label: "Taxi Controller",
                value: "taxi controller"
            },
            {
                label: "Taxi Driver",
                value: "taxi driver"
            },
            {
                label: "Taxidermist",
                value: "taxidermist"
            },
            {
                label: "Tea Blender",
                value: "tea blender"
            },
            {
                label: "Tea Taster",
                value: "tea taster"
            },
            {
                label: "Teacher",
                value: "teacher"
            },
            {
                label: "Teachers Assistant",
                value: "teachers assistant"
            },
            {
                label: "Technical Advisor",
                value: "technical advisor"
            },
            {
                label: "Technical Analyst",
                value: "technical analyst"
            },
            {
                label: "Technical Assistant",
                value: "technical assistant"
            },
            {
                label: "Technical Author",
                value: "technical author"
            },
            {
                label: "Technical Clerk",
                value: "technical clerk"
            },
            {
                label: "Technical Co-ordinator",
                value: "technical co-ordinator"
            },
            {
                label: "Technical Director",
                value: "technical director"
            },
            {
                label: "Technical Editor",
                value: "technical editor"
            },
            {
                label: "Technical Engineer",
                value: "technical engineer"
            },
            {
                label: "Technical Illustrator",
                value: "technical illustrator"
            },
            {
                label: "Technical Instructor",
                value: "technical instructor"
            },
            {
                label: "Technical Liaison Engineer",
                value: "technical liaison engineer"
            },
            {
                label: "Technical Manager",
                value: "technical manager"
            },
            {
                label: "Technician",
                value: "technician"
            },
            {
                label: "Technician - Performing Arts",
                value: "technician - performing arts"
            },
            {
                label: "Technologist",
                value: "technologist"
            },
            {
                label: "Telecommunication Consultant",
                value: "telecommunication consultant"
            },
            {
                label: "Telecommunications Engineer",
                value: "telecommunications engineer"
            },
            {
                label: "Telecommunications Manager",
                value: "telecommunications manager"
            },
            {
                label: "Telegraphist",
                value: "telegraphist"
            },
            {
                label: "Telemarketer",
                value: "telemarketer"
            },
            {
                label: "Telephone Engineer",
                value: "telephone engineer"
            },
            {
                label: "Telephonist",
                value: "telephonist"
            },
            {
                label: "Telesales Person",
                value: "telesales person"
            },
            {
                label: "Television Director",
                value: "television director"
            },
            {
                label: "Television Engineer",
                value: "television engineer"
            },
            {
                label: "Television Presenter",
                value: "television presenter"
            },
            {
                label: "Television Producer",
                value: "television producer"
            },
            {
                label: "Television Set Builder",
                value: "television set builder"
            },
            {
                label: "Telex Operator",
                value: "telex operator"
            },
            {
                label: "Temperature Time Recorder",
                value: "temperature time recorder"
            },
            {
                label: "Tennis Coach",
                value: "tennis coach"
            },
            {
                label: "Terrier",
                value: "terrier"
            },
            {
                label: "Test Driver",
                value: "test driver"
            },
            {
                label: "Test Engineer",
                value: "test engineer"
            },
            {
                label: "Textile Consultant",
                value: "textile consultant"
            },
            {
                label: "Textile Engineer",
                value: "textile engineer"
            },
            {
                label: "Textile Technician",
                value: "textile technician"
            },
            {
                label: "Textile Worker",
                value: "textile worker"
            },
            {
                label: "Thatcher",
                value: "thatcher"
            },
            {
                label: "Theatre Director",
                value: "theatre director"
            },
            {
                label: "Theatre Manager",
                value: "theatre manager"
            },
            {
                label: "Theatre Technician",
                value: "theatre technician"
            },
            {
                label: "Theatrical Agent",
                value: "theatrical agent"
            },
            {
                label: "Theme Park Worker",
                value: "theme park worker"
            },
            {
                label: "Therapist",
                value: "therapist"
            },
            {
                label: "Thermal Engineer",
                value: "thermal engineer"
            },
            {
                label: "Thermal Insulator",
                value: "thermal insulator"
            },
            {
                label: "Ticket Agent",
                value: "ticket agent"
            },
            {
                label: "Ticket Collector",
                value: "ticket collector"
            },
            {
                label: "Ticket Inspector",
                value: "ticket inspector"
            },
            {
                label: "Tiler",
                value: "tiler"
            },
            {
                label: "Timber Inspector",
                value: "timber inspector"
            },
            {
                label: "Timber Worker",
                value: "timber worker"
            },
            {
                label: "Time and Motion Analyst",
                value: "time and motion analyst"
            },
            {
                label: "Tobacconist",
                value: "tobacconist"
            },
            {
                label: "Toll Collector",
                value: "toll collector"
            },
            {
                label: "Tool Maker",
                value: "tool maker"
            },
            {
                label: "Tool Setter",
                value: "tool setter"
            },
            {
                label: "Tour Agent",
                value: "tour agent"
            },
            {
                label: "Tour Guide",
                value: "tour guide"
            },
            {
                label: "Town Clerk",
                value: "town clerk"
            },
            {
                label: "Town Planner",
                value: "town planner"
            },
            {
                label: "Toy Maker",
                value: "toy maker"
            },
            {
                label: "Toy Trader",
                value: "toy trader"
            },
            {
                label: "Track Worker",
                value: "track worker"
            },
            {
                label: "Tractor Driver",
                value: "tractor driver"
            },
            {
                label: "Tractor Mechanic",
                value: "tractor mechanic"
            },
            {
                label: "Trade Mark Agent",
                value: "trade mark agent"
            },
            {
                label: "Trade Union Official",
                value: "trade union official"
            },
            {
                label: "Trading Standards Officer",
                value: "trading standards officer"
            },
            {
                label: "Traffic Clerk",
                value: "traffic clerk"
            },
            {
                label: "Traffic Despatcher",
                value: "traffic despatcher"
            },
            {
                label: "Traffic Engineer",
                value: "traffic engineer"
            },
            {
                label: "Traffic Officer",
                value: "traffic officer"
            },
            {
                label: "Traffic Planner",
                value: "traffic planner"
            },
            {
                label: "Traffic Supervisor",
                value: "traffic supervisor"
            },
            {
                label: "Traffic Warden",
                value: "traffic warden"
            },
            {
                label: "Train Despatcher",
                value: "train despatcher"
            },
            {
                label: "Train Driver",
                value: "train driver"
            },
            {
                label: "Train Motorman",
                value: "train motorman"
            },
            {
                label: "Trainee Manager",
                value: "trainee manager"
            },
            {
                label: "Trainer",
                value: "trainer"
            },
            {
                label: "Trainer - Animal",
                value: "trainer - animal"
            },
            {
                label: "Trainer - Greyhound",
                value: "trainer - greyhound"
            },
            {
                label: "Trainer - Race Horse",
                value: "trainer - race horse"
            },
            {
                label: "Training Advisor",
                value: "training advisor"
            },
            {
                label: "Training Assistant",
                value: "training assistant"
            },
            {
                label: "Training Consultant",
                value: "training consultant"
            },
            {
                label: "Training Co-ordinator",
                value: "training co-ordinator"
            },
            {
                label: "Training Instructor",
                value: "training instructor"
            },
            {
                label: "Training Manager",
                value: "training manager"
            },
            {
                label: "Training Officer",
                value: "training officer"
            },
            {
                label: "Transcriber",
                value: "transcriber"
            },
            {
                label: "Translator",
                value: "translator"
            },
            {
                label: "Transmission Controller",
                value: "transmission controller"
            },
            {
                label: "Transport Clerk",
                value: "transport clerk"
            },
            {
                label: "Transport Consultant",
                value: "transport consultant"
            },
            {
                label: "Transport Controller",
                value: "transport controller"
            },
            {
                label: "Transport Engineer",
                value: "transport engineer"
            },
            {
                label: "Transport Manager",
                value: "transport manager"
            },
            {
                label: "Transport Officer",
                value: "transport officer"
            },
            {
                label: "Transport Planner",
                value: "transport planner"
            },
            {
                label: "Transport Policeman",
                value: "transport policeman"
            },
            {
                label: "Travel Agent",
                value: "travel agent"
            },
            {
                label: "Travel Clerk",
                value: "travel clerk"
            },
            {
                label: "Travel Consultant",
                value: "travel consultant"
            },
            {
                label: "Travel Courier",
                value: "travel courier"
            },
            {
                label: "Travel Guide",
                value: "travel guide"
            },
            {
                label: "Travel Guide Writer",
                value: "travel guide writer"
            },
            {
                label: "Travel Representative",
                value: "travel representative"
            },
            {
                label: "Travelling Salesman / Woman",
                value: "travelling salesman / woman"
            },
            {
                label: "Travelling Showman",
                value: "travelling showman"
            },
            {
                label: "Trawler Hand",
                value: "trawler hand"
            },
            {
                label: "Treasurer",
                value: "treasurer"
            },
            {
                label: "Tree Feller",
                value: "tree feller"
            },
            {
                label: "Tree Surgeon",
                value: "tree surgeon"
            },
            {
                label: "Trichologist",
                value: "trichologist"
            },
            {
                label: "Trinity House Pilot",
                value: "trinity house pilot"
            },
            {
                label: "Trout Farmer",
                value: "trout farmer"
            },
            {
                label: "Truck Driver",
                value: "truck driver"
            },
            {
                label: "T-Shirt Printer",
                value: "t-shirt printer"
            },
            {
                label: "Tug Skipper",
                value: "tug skipper"
            },
            {
                label: "Tunneller",
                value: "tunneller"
            },
            {
                label: "Turf Accountant",
                value: "turf accountant"
            },
            {
                label: "Turkey Farmer",
                value: "turkey farmer"
            },
            {
                label: "Turner",
                value: "turner"
            },
            {
                label: "Tutor",
                value: "tutor"
            },
            {
                label: "TV And Video Installer",
                value: "tv and video installer"
            },
            {
                label: "TV And Video Repairer",
                value: "tv and video repairer"
            },
            {
                label: "TV Announcer",
                value: "tv announcer"
            },
            {
                label: "TV Broadcasting Technician",
                value: "tv broadcasting technician"
            },
            {
                label: "TV Editor",
                value: "tv editor"
            },
            {
                label: "Typesetter",
                value: "typesetter"
            },
            {
                label: "Typewriter Engineer",
                value: "typewriter engineer"
            },
            {
                label: "Typist",
                value: "typist"
            },
            {
                label: "Typographer",
                value: "typographer"
            },
            {
                label: "Tyre Builder",
                value: "tyre builder"
            },
            {
                label: "Tyre Fitter",
                value: "tyre fitter"
            },
            {
                label: "Tyre Inspector",
                value: "tyre inspector"
            },
            {
                label: "Tyre Technician",
                value: "tyre technician"
            },
            {
                label: "Tyre / Exhaust Fitter",
                value: "tyre / exhaust fitter"
            },
            {
                label: "Umpire",
                value: "umpire"
            },
            {
                label: "Undergraduate Student - Living At Home",
                value: "undergraduate student - living at home"
            },
            {
                label: "Undergraduate Student - Living Away from Home",
                value: "undergraduate student - living away from home"
            },
            {
                label: "Undertaker",
                value: "undertaker"
            },
            {
                label: "Underwriter",
                value: "underwriter"
            },
            {
                label: "Unemployed",
                value: "unemployed"
            },
            {
                label: "University Dean",
                value: "university dean"
            },
            {
                label: "University Reader",
                value: "university reader"
            },
            {
                label: "Unknown",
                value: "unknown"
            },
            {
                label: "Upholsterer",
                value: "upholsterer"
            },
            {
                label: "Usher",
                value: "usher"
            },
            {
                label: "Valet",
                value: "valet"
            },
            {
                label: "Valuer",
                value: "valuer"
            },
            {
                label: "Valve Technician",
                value: "valve technician"
            },
            {
                label: "Van Driver",
                value: "van driver"
            },
            {
                label: "Van Salesman",
                value: "van salesman"
            },
            {
                label: "VDU Operator",
                value: "vdu operator"
            },
            {
                label: "Vehicle Assessor",
                value: "vehicle assessor"
            },
            {
                label: "Vehicle Body Worker",
                value: "vehicle body worker"
            },
            {
                label: "Vehicle Bodybuilder",
                value: "vehicle bodybuilder"
            },
            {
                label: "Vehicle Delivery Agent",
                value: "vehicle delivery agent"
            },
            {
                label: "Vehicle Engineer",
                value: "vehicle engineer"
            },
            {
                label: "Vehicle Technician",
                value: "vehicle technician"
            },
            {
                label: "Vending Machine Filler",
                value: "vending machine filler"
            },
            {
                label: "Vending Machine Technician",
                value: "vending machine technician"
            },
            {
                label: "Ventriloquist",
                value: "ventriloquist"
            },
            {
                label: "Verger",
                value: "verger"
            },
            {
                label: "Veterinary Assistant",
                value: "veterinary assistant"
            },
            {
                label: "Veterinary Nurse",
                value: "veterinary nurse"
            },
            {
                label: "Veterinary Surgeon",
                value: "veterinary surgeon"
            },
            {
                label: "Vicar",
                value: "vicar"
            },
            {
                label: "Video Supplier",
                value: "video supplier"
            },
            {
                label: "Videotape Engineer",
                value: "videotape engineer"
            },
            {
                label: "Violin Maker",
                value: "violin maker"
            },
            {
                label: "Vision Control Manager",
                value: "vision control manager"
            },
            {
                label: "Vision Mixer",
                value: "vision mixer"
            },
            {
                label: "Voluntary Worker",
                value: "voluntary worker"
            },
            {
                label: "Volcanologist",
                value: "volcanologist"
            },
            {
                label: "Wages Clerk",
                value: "wages clerk"
            },
            {
                label: "Waiter",
                value: "waiter"
            },
            {
                label: "Waiter / Waitress - Licensed",
                value: "waiter / waitress - licensed"
            },
            {
                label: "Waiter / Waitress - Unlicensed",
                value: "waiter / waitress - unlicensed"
            },
            {
                label: "Waitress",
                value: "waitress"
            },
            {
                label: "Warden",
                value: "warden"
            },
            {
                label: "Wardrobe Mistress",
                value: "wardrobe mistress"
            },
            {
                label: "Warehouse Manager",
                value: "warehouse manager"
            },
            {
                label: "Warehouseman",
                value: "warehouseman"
            },
            {
                label: "Warehouseman / Woman",
                value: "warehouseman / woman"
            },
            {
                label: "Warehousewoman",
                value: "warehousewoman"
            },
            {
                label: "Waste Dealer",
                value: "waste dealer"
            },
            {
                label: "Waste Disposal Worker",
                value: "waste disposal worker"
            },
            {
                label: "Waste Paper Merchant",
                value: "waste paper merchant"
            },
            {
                label: "Watchmaker",
                value: "watchmaker"
            },
            {
                label: "Water Diviner",
                value: "water diviner"
            },
            {
                label: "Water Industry Worker",
                value: "water industry worker"
            },
            {
                label: "Weaver",
                value: "weaver"
            },
            {
                label: "Web Designer",
                value: "web designer"
            },
            {
                label: "Web Developer",
                value: "web developer"
            },
            {
                label: "Web Programmer",
                value: "web programmer"
            },
            {
                label: "Weighbridge Clerk",
                value: "weighbridge clerk"
            },
            {
                label: "Weighbridge Operator",
                value: "weighbridge operator"
            },
            {
                label: "Welder",
                value: "welder"
            },
            {
                label: "Welfare Assistant",
                value: "welfare assistant"
            },
            {
                label: "Welfare Officer",
                value: "welfare officer"
            },
            {
                label: "Welfare Rights Officer",
                value: "welfare rights officer"
            },
            {
                label: "Wheel Clamper",
                value: "wheel clamper"
            },
            {
                label: "Wheelwright",
                value: "wheelwright"
            },
            {
                label: "Whisky Blender",
                value: "whisky blender"
            },
            {
                label: "Wholesale Newspaper Delivery Driver",
                value: "wholesale newspaper delivery driver"
            },
            {
                label: "Will Writer",
                value: "will writer"
            },
            {
                label: "Window Cleaner",
                value: "window cleaner"
            },
            {
                label: "Window Dresser",
                value: "window dresser"
            },
            {
                label: "Windscreen Fitter",
                value: "windscreen fitter"
            },
            {
                label: "Wine Merchant",
                value: "wine merchant"
            },
            {
                label: "Wood Carver",
                value: "wood carver"
            },
            {
                label: "Wood Cutter",
                value: "wood cutter"
            },
            {
                label: "Wood Machinist",
                value: "wood machinist"
            },
            {
                label: "Wood Worker",
                value: "wood worker"
            },
            {
                label: "Word Processing Operator",
                value: "word processing operator"
            },
            {
                label: "Work Study Analyst",
                value: "work study analyst"
            },
            {
                label: "Works Manager",
                value: "works manager"
            },
            {
                label: "Writer",
                value: "writer"
            },
            {
                label: "Yacht Master",
                value: "yacht master"
            },
            {
                label: "Yard Man",
                value: "yard man"
            },
            {
                label: "Yard Manager",
                value: "yard manager"
            },
            {
                label: "Yoga Teacher",
                value: "yoga teacher"
            },
            {
                label: "Youth Hostel Warden",
                value: "youth hostel warden"
            },
            {
                label: "Youth Worker",
                value: "youth worker"
            },
            {
                label: "Zoo Keeper",
                value: "zoo keeper"
            },
            {
                label: "Zoo Manager",
                value: "zoo manager"
            },
            {
                label: "Zoologist",
                value: "zoologist"
            },
            {
                label: "Zoology Consultant",
                value: "zoology consultant"
            }
        ],
        filteredOptions() {
            return this.options.filter((option) => {
                return option.value.includes(this.search.toLowerCase());
            });
        }
    };
}

</script>