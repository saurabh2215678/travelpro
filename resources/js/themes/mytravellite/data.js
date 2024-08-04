const searchResult = {
  tripInfos : {
      "ONWARD": []
  }
};

const paxInfo = {"ADULT": "1"};

const routeInfos = [];

const airportLists = []

const form_data = {}

const destinations = {
  data: [
    {title : 'Ladakh', url: '#', thumbSrc: './assets/traveltour/images/destination1.jpg'},
    {title : 'Rajasthan', url: '#', thumbSrc: './assets/traveltour/images/destination2.jpg'},
    {title : 'Goa', url: '#', thumbSrc: './assets/traveltour/images/destination3.jpg'},
    {title : 'Kerala', url: '#', thumbSrc: './assets/traveltour/images/destination4.jpg'},
    {title : 'Varanasi', url: '#', thumbSrc: './assets/traveltour/images/destination5.jpg'},
    {title : 'Himachal Pradesh', url: '#', thumbSrc: './assets/traveltour/images/destination6.jpg'}
  ],
  url: "#"
}

const clients = [
  {image : './assets/traveltour/images/client1.png'},
  {image : './assets/traveltour/images/client2.png'},
  {image : './assets/traveltour/images/client3.png'},
  {image : './assets/traveltour/images/client4.png'},
  {image : './assets/traveltour/images/client5.png'},
  {image : './assets/traveltour/images/client6.png'},
  {image : './assets/traveltour/images/client1.png'},
  {image : './assets/traveltour/images/client2.png'},
  {image : './assets/traveltour/images/client3.png'},
  {image : './assets/traveltour/images/client4.png'},
  {image : './assets/traveltour/images/client5.png'},
  {image : './assets/traveltour/images/client6.png'},
  {image : './assets/traveltour/images/client1.png'},
  {image : './assets/traveltour/images/client2.png'},
  {image : './assets/traveltour/images/client3.png'},
  {image : './assets/traveltour/images/client4.png'},
  {image : './assets/traveltour/images/client5.png'},
  {image : './assets/traveltour/images/client6.png'},
  {image : './assets/traveltour/images/client1.png'},
  {image : './assets/traveltour/images/client2.png'},
  {image : './assets/traveltour/images/client3.png'},
  {image : './assets/traveltour/images/client4.png'},
  {image : './assets/traveltour/images/client5.png'},
  {image : './assets/traveltour/images/client6.png'}
];

const dummyPackageData = [
  {
    url : '#',
    thumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Dummy 1',
    search_price: 1000,
    package_type: 'Flexi package',
    days: 4,
    nights: 3
  },
  {
    url : '#',
    thumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Dummy 2',
    search_price: 1000,
    package_type: 'Flexi package',
    days: 4,
    nights: 3
  },
  {
    url : '#',
    thumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Dummy 3',
    search_price: 1000,
    package_type: 'Flexi package',
    days: 4,
    nights: 3
  },
  {
    url : '#',
    thumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Dummy 4',
    search_price: 1000,
    package_type: 'Flexi package',
    days: 4,
    nights: 3
  }
];

const dummyBlogs = [
  {
    url : '/blog/detail/testing-usewr',
    blogthumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    image : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Test Blog',
    author : 'Test Author',
    date : '22 Jun 2023',
    categories : [{id: 1, name: 'cat1', url:'/blog/culture'}, {id: 2, name: 'cat2', url:'/blog/culture'}],
    socialLinks : [{fb : 'https://www.facebook.com/', twitter: 'https://twitter.com/', whatsapp: 'https://web.whatsapp.com'}],
    description : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."
  },
  {
    url : '/blog/detail/testing-usewr',
    blogthumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    image : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Test Blog',
    author : 'Test Author',
    date : '22 Jun 2023',
    categories : [{id: 1, name: 'cat1'}, {id: 2, name: 'cat2'}],
    socialLinks : [{fb : 'https://www.facebook.com/', twitter: 'https://twitter.com/', whatsapp: 'https://web.whatsapp.com'}],
    description : 'Test Description'
  },
  {
    url : '/blog/detail/testing-usewr',
    blogthumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    image : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Test Blog',
    author : 'Test Author',
    date : '22 Jun 2023',
    categories : [{id: 1, name: 'cat1'}, {id: 2, name: 'cat2'}],
    socialLinks : [{fb : 'https://www.facebook.com/', twitter: 'https://twitter.com/', whatsapp: 'https://web.whatsapp.com'}],
    description : 'Test Description'
  },
  {
    url : '/blog/detail/testing-usewr',
    blogthumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    image : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Test Blog',
    author : 'Test Author',
    date : '22 Jun 2023',
    categories : [{id: 1, name: 'cat1'}, {id: 2, name: 'cat2'}],
    socialLinks : [{fb : 'https://www.facebook.com/', twitter: 'https://twitter.com/', whatsapp: 'https://web.whatsapp.com'}],
    description : 'Test Description'
  },
  {
    url : '/blog/detail/testing-usewr',
    blogthumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    image : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Test Blog',
    author : 'Test Author',
    date : '22 Jun 2023',
    categories : [{id: 1, name: 'cat1'}, {id: 2, name: 'cat2'}],
    socialLinks : [{fb : 'https://www.facebook.com/', twitter: 'https://twitter.com/', whatsapp: 'https://web.whatsapp.com'}],
    description : 'Test Description'
  },
  {
    url : '/blog/detail/testing-usewr',
    blogthumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    image : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Test Blog',
    author : 'Test Author',
    date : '22 Jun 2023',
    categories : [{id: 1, name: 'cat1'}, {id: 2, name: 'cat2'}],
    socialLinks : [{fb : 'https://www.facebook.com/', twitter: 'https://twitter.com/', whatsapp: 'https://web.whatsapp.com'}],
    description : 'Test Description'
  },
  {
    url : '/blog/detail/testing-usewr',
    blogthumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    image : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Test Blog',
    author : 'Test Author',
    date : '22 Jun 2023',
    categories : [{id: 1, name: 'cat1'}, {id: 2, name: 'cat2'}],
    socialLinks : [{fb : 'https://www.facebook.com/', twitter: 'https://twitter.com/', whatsapp: 'https://web.whatsapp.com'}],
    description : 'Test Description'
  },
  {
    url : '/blog/detail/testing-usewr',
    blogthumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    image : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Test Blog',
    author : 'Test Author',
    date : '22 Jun 2023',
    categories : [{id: 1, name: 'cat1'}, {id: 2, name: 'cat2'}],
    socialLinks : [{fb : 'https://www.facebook.com/', twitter: 'https://twitter.com/', whatsapp: 'https://web.whatsapp.com'}],
    description : 'Test Description'
  },
  {
    url : '/blog/detail/testing-usewr',
    blogthumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    image : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Test Blog',
    author : 'Test Author',
    date : '22 Jun 2023',
    categories : [{id: 1, name: 'cat1'}, {id: 2, name: 'cat2'}],
    socialLinks : [{fb : 'https://www.facebook.com/', twitter: 'https://twitter.com/', whatsapp: 'https://web.whatsapp.com'}],
    description : 'Test Description'
  },
  
];

const dummyTestimonials = [
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
];
const blogCategories = [
  {
    url : '/blog/culture',
    title : 'Category1'
  },
  {
    url : '/blog/culture',
    title : 'Category2'
  },
  {
    url : '/blog/culture',
    title : 'Category3'
  },
  {
    url : '/blog/culture',
    title : 'Category4'
  },
  {
    url : '/blog/culture',
    title : 'Category5'
  },
  {
    url : '/blog/culture',
    title : 'Category6'
  },
  {
    url : '/blog/culture',
    title : 'Category7'
  },
  {
    url : '/blog/culture',
    title : 'Category8'
  },
]

const dummyTeam = [
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 1',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 2',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 3',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 4',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 5',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 6',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 7',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 8',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 9',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 10',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 11',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 12',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
]

const cmsData = {
    image : '/assets/traveltour/images/blog-and-news.jpg',
    content : `<p></p><p>Overland Escape was established in August 1999 and now is one of the leading travel companies responsible for marketing Ladakh worldwide and India as well, thereby contributing in a positive way in developing economy of Ladakh. In order to ensure that Ladakh is marketed in an inspirational and relevant way we have been working vigorously with our partners both in India and overseas. Ever since its inception, Overland Escape has cultivated the friendship and cooperation in the field of travel related services with its valuable clients. We offer custom-made tour packages of India, Nepal, Bhutan and Tibet at competitive prices and have established ourselves as a travel company with interesting inbound and outbound tour itineraries. We are backed up by a quality management system.</p><p>We at Overland believe in protecting the character of the destination we work on i.e. preserving the different cultures and traditions around us which inspires us to remain passionate about travelling and helping others to experience the destinations that we treasure. Protecting the natural environment through the promotion and support of various conservation efforts is also our priority. One of the initiatives of Overland in this field is the annual garbage cleaning trek which is held every year around September.</p><p>Training and employing local personnel is what we believe in, thus enhancing the dimension of your visit and supporting the economy of the local communities. We are also a reliable partner aiming to help improve the efficiency, productivity, standing and success of our esteemed customers. <br><br></p><p></p>
        
    <p>Overland Escape was established in August 1999 and now is one of the leading travel companies responsible for marketing Ladakh worldwide and India as well, thereby contributing in a positive way in developing economy of Ladakh. In order to ensure that Ladakh is marketed in an inspirational and relevant way we have been working vigorously with our partners both in India and overseas. Ever since its inception, Overland Escape has cultivated the friendship and cooperation in the field of travel related services with its valuable clients. We offer custom-made tour packages of India, Nepal, Bhutan and Tibet at competitive prices and have established ourselves as a travel company with interesting inbound and outbound tour itineraries. We are backed up by a quality management system.</p>

    <p>We at Overland believe in protecting the character of the destination we work on i.e. preserving the different cultures and traditions around us which inspires us to remain passionate about travelling and helping others to experience the destinations that we treasure. Protecting the natural environment through the promotion and support of various conservation efforts is also our priority. One of the initiatives of Overland in this field is the annual garbage cleaning trek which is held every year around September.</p>

    <p>Training and employing local personnel is what we believe in, thus enhancing the dimension of your visit and supporting the economy of the local communities. We are also a reliable partner aiming to help improve the efficiency, productivity, standing and success of our esteemed customers.<br>
    &nbsp;</p>`
  }

const dummyFaqs = [
  {
    id : 1,
    question: 'Where is Andaman and Nicobar Islands?',
    answer: `
    <p>A union territory of India, the Andaman and Nicobar Islands consist of as many as 572 islands. Of these, only 38 are inhabited. The island is at the juncture of the Bay of Bengal and the Andaman Sea. Nearly 150 kilometers north of Aceh in Indonesia, the territory is separated from Myanmar and Thailand by the Andaman Sea. Two island groups are comprised in it— the Andaman Islands (partly) and the Nicobar Islands. The 150-kilometer wide Ten Degree Channel (on the 10°N parallel) separates them.</p>
    <p>Port Blair is the capital of the Andaman and Nicobar Islands. The total land area of the islands is around 8,249 square kilometers. It is divided into three districts:</p>
    <ol>
      <li>The Nicobar District with Car Nicobar as its capital</li>
      <li>The South Andaman district with Port Blair as its capital</li>
      <li>The North and Middle Andaman district with Mayabunder as its capital</li>
    </ol>
    `
  },
  {
    id : 2,
    question: 'Which is the best island to visit in Andaman?',
    answer: `
    <p>Geographically, the Andamans are located in the east of the mainland of India. Featuring secluded beaches bordering the azure cobalt ocean, the Andaman and Nicobar Islands are blessed with the best of nature. The islands of this union territory of India are proofs of God’s vivid imagination. The golden and white sands of the beaches meeting the turquoise waters of the ocean and the green shades of lush forests creating scenery in the archipelago is beautiful beyond anything else!</p>
    <p>The Andaman and Nicobar Islands comprise gorgeous islands. The best among them are as follows:</p>
    <ul>
       <li><a href="https://www.andamanisland.in/destination/havelock-island">Havelock Island</a></li>
       <li><a href="https://www.andamanisland.in/blog/detail/jolly-buoy-island-red-skin-island-at-port-blair">Jolly Buoy Island</a></li>
       <li><a href="https://www.andamanisland.in/destination/neil-island">Neil Island</a></li>
       <li><a href="https://www.andamanisland.in/blog/detail/how-to-reach-ross-island">Ross Island</a></li>
       <li><a href="https://www.andamanisland.in/blog/detail/ross-island-north-bay-island-at-port-blair">North Bay Island</a></li>
       <li><a href="https://www.andamanisland.in/content/ross-smith-island">Ross and Smith Island</a></li>
       <li><a href="https://www.andamanisland.in/blog/detail/cinque-island-at-port-blair">Cinque Island</a></li>
       <li><a href="https://www.andamanisland.in/destination/baratang-island">Baratang Island</a></li>
       <li><a href="https://www.andamanisland.in/blog/detail/jolly-buoy-island-red-skin-island-at-port-blair">Red Skin Island</a></li>
       <li><a href="https://www.andamanisland.in/blog/detail/barren-island-tour">Barren Island</a></li>
       <li>Inglis Island</li>
       <li><a href="https://www.andamanisland.in/blog/detail/parrot-island-at-baratang-island">Parrot Island</a></li>
       <li><a href="https://www.andamanisland.in/destination/mayabunder-island">Mayabunder Island</a></li>
       <li><a href="https://www.andamanisland.in/destination/diglipur-island">Diglipur Island</a></li>
    </ul>
    `
  },
  {
    id : 3,
    question: 'Which is the best island to visit in Andaman?',
    answer: `
    <p>You can visit the Andaman and Nicobar Islands all around the year. Having its own charm in each and every season, there is a lot to explore in the island destination. The Andamans are popular for the green-blue waters, pure beauty, along with the mental peace it provides to the travellers. A majority of the tourists visit Andaman during the months from October to May. At this time, the downpour has its own charm. The natural beauty is absolutely mesmerising. The calming waters and lush greenery further accentuate the beauty of the tropical island.</p>
    <p>You can pick any season to visit the beautiful Andaman and Nicobar Islands:</p>
    <ul>
      <li>Summer- April to June</li>
      <li>Monsoon- July to September</li>
      <li>Winter- October to March</li>
    </ul>
    <p>Just make sure that you have packed accordingly.</p>
    `
  },
  {
    id : 4,
    question: 'Which is the best island to visit in Andaman?',
    answer: `
    <p style="box-sizing: border-box; margin: 0px 0px 15px; font-size: 17px; color: #666666; line-height: 28px; font-family: Lato, sans-serif; outline: 0px !important;"><span style="font-size: 12pt;">Andaman Island is the perfect destination for those who love nature and adventure. There are a number of places to see here that are sure to take your breath away. However, it is also very important that you enjoy your stay here as well. Some people prefer a beachfront resort property while others want to stay in a hotel located in the main market. Requirements of all kinds of guests can be easily fulfilled in the Andamans as it has grand and lavish beachfront properties as well as budget hotels across all the tourist destinations.</span></p>
    <p style="box-sizing: border-box; margin: 0px 0px 15px; font-size: 17px; color: #666666; line-height: 28px; font-family: Lato, sans-serif; outline: 0px !important;"><span style="font-size: 12pt;">If you want to stay at a beachfront resort, then we would highly recommend <a href="https://www.andamanisland.in/hotel/havelock-island-beach-resort">Havelock Island Beach Resort</a> as it is well-equipped with all the modern amenities and so much more! Its location is excellent as well. You can spend quality time at its private beach.</span></p>
    `
  }
]
export {
  searchResult, 
  paxInfo, 
  routeInfos, 
  airportLists, 
  form_data, 
  destinations, 
  clients, 
  dummyPackageData, 
  dummyBlogs, 
  blogCategories,
  dummyTestimonials,
  dummyTeam,
  cmsData,
  dummyFaqs
};