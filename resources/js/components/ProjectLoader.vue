<template>
    <!-- Loader Shimmer -->
  <Card v-for="n in 4" :key="n" class="py-6 space-y-4 border-0 shadow-sm">
    <CardContent>
      <div class="flex justify-between items-start mb-4">
        <div class="flex-1 min-w-0 space-y-2">
          <Skeleton class="h-6 w-3/4" /> <!-- title -->
          <Skeleton class="h-4 w-full" /> <!-- description -->
          <Skeleton class="h-4 w-2/3" />
        </div>
        <Skeleton class="h-8 w-8 rounded-md" /> <!-- menu button -->
      </div>

      <!-- Progress -->
      <div class="space-y-2 mb-4">
        <Skeleton class="h-4 w-24" />
        <Skeleton class="h-3 w-full rounded-full" />
      </div>

      <!-- Project Meta -->
      <div class="flex justify-between">
        <Skeleton class="h-4 w-20" />
        <Skeleton class="h-4 w-16" />
      </div>
    </CardContent>
  </Card>
</template>


<script setup>
import { Card, CardContent } from '@/components/ui/card'
import {Skeleton} from '@/components/ui/skeleton'
</script>