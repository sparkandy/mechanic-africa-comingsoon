<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Organization",
      "@id": "{{ url('/') }}#organization",
      "name": "Mechanic Africa",
      "url": "{{ url('/') }}",
      "logo": {
        "@type": "ImageObject",
        "url": "{{ asset('images/mechanic-africa-logo.png') }}",
        "width": 600,
        "height": 60
      },
      "description": "Professional car maintenance and oil change services across Nigeria with certified mechanics and genuine parts.",
      "address": {
        "@type": "PostalAddress",
        "addressCountry": "NG",
        "addressRegion": "Lagos",
        "addressLocality": "Nigeria"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": "6.5244",
        "longitude": "3.3792"
      },
      "contactPoint": {
        "@type": "ContactPoint",
        "contactType": "Customer Service",
        "areaServed": "NG",
        "availableLanguage": ["English"]
      },
      "sameAs": [
        "https://www.facebook.com/MechanicAfrica",
        "https://www.twitter.com/MechanicAfrica",
        "https://www.instagram.com/MechanicAfrica",
        "https://www.linkedin.com/company/mechanic-africa"
      ]
    },
    {
      "@type": "WebSite",
      "@id": "{{ url('/') }}#website",
      "url": "{{ url('/') }}",
      "name": "Mechanic Africa",
      "description": "Professional Car Maintenance & Oil Change Services in Nigeria",
      "publisher": {
        "@id": "{{ url('/') }}#organization"
      },
      "potentialAction": {
        "@type": "SearchAction",
        "target": {
          "@type": "EntryPoint",
          "urlTemplate": "{{ url('/') }}?s={search_term_string}"
        },
        "query-input": "required name=search_term_string"
      }
    },
    {
      "@type": "WebPage",
      "@id": "{{ url('/') }}#webpage",
      "url": "{{ url('/') }}",
      "name": "Mechanic Africa - Professional Car Maintenance & Oil Change Services",
      "isPartOf": {
        "@id": "{{ url('/') }}#website"
      },
      "about": {
        "@id": "{{ url('/') }}#organization"
      },
      "description": "Expert car maintenance, oil change, and vehicle servicing across Nigeria. Certified mechanics, genuine parts, transparent pricing.",
      "inLanguage": "en-NG"
    },
    {
      "@type": "Service",
      "@id": "{{ url('/') }}#service",
      "serviceType": "Car Maintenance and Oil Change",
      "provider": {
        "@id": "{{ url('/') }}#organization"
      },
      "areaServed": {
        "@type": "Country",
        "name": "Nigeria"
      },
      "hasOfferCatalog": {
        "@type": "OfferCatalog",
        "name": "Car Maintenance Services",
        "itemListElement": [
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "4 Cylinders Engine Service",
              "description": "Complete oil change and maintenance for 4-cylinder engines"
            },
            "price": "60000",
            "priceCurrency": "NGN"
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "6 Cylinders Engine Service",
              "description": "Complete oil change and maintenance for 6-cylinder engines"
            },
            "price": "70000",
            "priceCurrency": "NGN"
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "8 Cylinders Engine Service",
              "description": "Complete oil change and maintenance for 8-cylinder engines"
            },
            "price": "90000",
            "priceCurrency": "NGN"
          }
        ]
      },
      "offers": {
        "@type": "AggregateOffer",
        "priceCurrency": "NGN",
        "lowPrice": "60000",
        "highPrice": "90000",
        "priceSpecification": {
          "@type": "PriceSpecification",
          "priceCurrency": "NGN",
          "price": "60000"
        }
      }
    },
    {
      "@type": "BreadcrumbList",
      "@id": "{{ url('/') }}#breadcrumb",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "{{ url('/') }}"
        }
      ]
    },
    {
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "What services does Mechanic Africa offer?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Mechanic Africa offers professional car maintenance services including oil changes, engine diagnostics, vehicle inspections, and comprehensive servicing for 4, 6, and 8-cylinder engines across Nigeria."
          }
        },
        {
          "@type": "Question",
          "name": "How much does car maintenance cost at Mechanic Africa?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Our pricing starts from ₦60,000 for 4-cylinder engines, ₦70,000 for 6-cylinder engines, and ₦90,000 for 8-cylinder engines. All services include genuine parts and certified mechanics."
          }
        },
        {
          "@type": "Question",
          "name": "Are Mechanic Africa's technicians certified?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes, all our mechanics are certified professionals with extensive experience in automotive maintenance and repair. We ensure quality service with genuine parts."
          }
        }
      ]
    }
  ]
}
</script>
